<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRentalRequest;
use App\Models\{Rental, Unit, Payment};
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function __construct(private PricingService $pricing) {}

    public function index(Request $request)
    {
        $rentals = Rental::with('unit.category')
            ->where('user_id', $request->user()->id)
            ->whereIn('status', ['ACTIVE','RETURN_REQUESTED'])
            ->orderByDesc('created_at')
            ->limit(2)
            ->get();
        return view('rentals.index', compact('rentals'));
    }

    public function store(StoreRentalRequest $request)
    {
        $this->ensureNotAdmin($request);

        $user = $request->user();
        $unit = Unit::with('category')->findOrFail($request->unit_id);
        abort_if($unit->is_sold, 422, 'Unit sudah terjual');
        abort_unless($unit->is_available, 422, 'Unit tidak tersedia');

        // Hitung slot aktif
        $active = Rental::with('unit')->where('user_id', $user->id)
            ->whereIn('status', ['PENDING_PAYMENT', 'ACTIVE', 'RETURN_REQUESTED'])->get();
        $activeSlots = $active->sum(fn($r)=> $r->unit->isSculptureDoubleSlot()?2:1);
        $incomingSlots = $unit->isSculptureDoubleSlot()?2:1;
        if ($activeSlots + $incomingSlots > 2) return back()->withErrors('Melebihi batas maksimal 2 unit.');

        // Eligibility + deposit
        $depositRequired = 0;
        if ($unit->requiresDeposit()) {
            $hasCleanHistory = Rental::where('user_id',$user->id)
                ->where('status','RETURNED')
                ->whereDoesntHave('penalties', fn($q)=>$q->whereIn('kind',['DAMAGE','LATE']))
                ->exists();
            if (!$hasCleanHistory) return back()->withErrors('Belum memenuhi syarat menyewa unit 60s/70s.');
            $depositRequired = $this->pricing->calcDeposit($unit);
        }

        $start = now();
        $plan  = (clone $start)->addDays(config('artcore.max_rental_days'));

        DB::transaction(function () use ($user, $unit, $start, $plan, $depositRequired) {
            $rental = Rental::create([
                'user_id'             => $user->id,
                'unit_id'             => $unit->id,
                'status'              => 'ACTIVE',
                'rental_start'        => $start,
                'rental_end_plan'     => $plan,
                'deposit_required'    => $depositRequired,
                'deposit_paid'        => $depositRequired,
                'rent_fee_paid'       => $unit->rent_price_5d,
                'eligibility_checked' => true,
            ]);

            $unit->update(['is_available' => false, 'is_sold' => false]);

            Payment::create([
                'rental_id' => $rental->id,
                'type'      => 'RENT_FEE',
                'amount'    => $unit->rent_price_5d,
                'method'    => 'CASH',
                'paid_at'   => now(),
            ]);

            if ($depositRequired > 0) {
                Payment::create([
                    'rental_id' => $rental->id,
                    'type'      => 'DEPOSIT',
                    'amount'    => $depositRequired,
                    'method'    => 'CASH',
                    'paid_at'   => now(),
                ]);
            }
        });

        $this->removeUnitFromCart($request, $unit->id);

        return redirect()->route('rentals.index')->with([
            'status' => 'Sewa dimulai. Jatuh tempo 5 hari.',
            'toast'  => 'Sewa dimulai. Jatuh tempo 5 hari.',
        ]);
    }

    // Trial-to-Own window 5 hari
    public function purchase(Request $request, Rental $rental)
    {
        $this->authorizeRentalOwner($request, $rental);
        abort_if($rental->status !== 'ACTIVE', 422, 'Rental tidak aktif.');
        abort_if(now()->diffInDays($rental->rental_start) > config('artcore.tpo_window_days'), 422, 'TPO sudah lewat.');

        $unit = $rental->unit()->firstOrFail();
        $final = $this->pricing->trialToOwnFinalPrice($unit, $rental);

        DB::transaction(function() use ($rental,$final) {
            // catat purchase
            $rental->purchase()->create([
                'decided_at'=>now(), 'final_price'=>$final, 'note'=>'TPO'
            ]);
            // catat payment final
            Payment::create([
                'rental_id'=>$rental->id,'type'=>'FINAL_PURCHASE','amount'=>$final,'method'=>'CASH','paid_at'=>now()
            ]);
            // transfer kepemilikan (opsional: buat tabel owners), set unit jadi unavailable permanen
            $rental->status = 'PURCHASED';
            $rental->save();
            $rental->unit()->update(['is_available'=>false,'is_sold'=>true]);
        });

        return back()->with([
            'status' => 'Berhasil dibeli melalui TPO. Terima kasih!',
            'toast'  => 'Berhasil dibeli melalui TPO. Terima kasih!',
        ]);
    }

    public function requestReturn(Request $request, Rental $rental)
    {
        $this->authorizeRentalOwner($request, $rental);
        abort_if($rental->status !== 'ACTIVE', 422, 'Pengembalian hanya untuk sewa aktif.');

        $rental->update([
            'status'              => 'RETURN_REQUESTED',
            'return_requested_at' => now(),
        ]);

        return back()->with([
            'status' => 'Permintaan pengembalian dikirim. Admin akan menghubungi Anda.',
            'toast'  => 'Permintaan pengembalian dikirim ke admin.',
        ]);
    }

    public function cart(Request $request)
    {
        $this->ensureNotAdmin($request);

        $cartIds = $this->getCartUnitIds($request);
        if (empty($cartIds)) {
            return view('user.cart-empty');
        }

        $units = Unit::with('category')->whereIn('id', $cartIds)->get()->keyBy('id');
        $ordered = collect($cartIds)
            ->map(fn($id) => $units->get($id))
            ->filter();

        $readyUnits = $ordered->filter(fn(Unit $unit) => $unit->is_available && !$unit->is_sold);

        if ($readyUnits->count() !== $ordered->count()) {
            $this->saveCartUnitIds($request, $readyUnits->pluck('id')->all());
        }

        if ($readyUnits->isEmpty()) {
            $this->saveCartUnitIds($request, []);
            return view('user.cart-empty');
        }

        $items = $readyUnits->map(function(Unit $unit) {
            $deposit = $this->pricing->calcDeposit($unit);
            $rent = (int) $unit->rent_price_5d;
            $slots = $unit->isSculptureDoubleSlot() ? 2 : 1;
            return [
                'unit'     => $unit,
                'deposit'  => $deposit,
                'rent'     => $rent,
                'subtotal' => $rent + $deposit,
                'slots'    => $slots,
            ];
        });

        $totals = [
            'rent'     => $items->sum('rent'),
            'deposit'  => $items->sum('deposit'),
            'overall'  => $items->sum('subtotal'),
            'cartSlots'=> $items->sum('slots'),
        ];

        $active = Rental::with('unit')->where('user_id', $request->user()->id)
            ->whereIn('status', ['PENDING_PAYMENT','ACTIVE','RETURN_REQUESTED'])->get();
        $activeSlots = $active->sum(fn($r)=> $r->unit->isSculptureDoubleSlot()?2:1);

        return view('user.cart', [
            'items'       => $items,
            'totals'      => $totals,
            'activeSlots' => $activeSlots,
            'maxSlots'    => 2,
        ]);
    }

    public function addToCart(Request $request)
    {
        $this->ensureNotAdmin($request);

        $request->validate([
            'unit_id' => ['required','integer','exists:units,id'],
        ]);

        $user = $request->user();
        $unit = Unit::with('category')->findOrFail($request->integer('unit_id'));
        if ($unit->is_sold) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unit sudah terjual.',
            ], 422);
        }
        if (!$unit->is_available) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unit tidak tersedia.',
            ], 422);
        }

        $cartIds = $this->getCartUnitIds($request);
        if (in_array($unit->id, $cartIds, true)) {
            return response()->json([
                'status'  => 'exists',
                'message' => 'Unit sudah ada di keranjang.',
            ]);
        }

        $active = Rental::with('unit')->where('user_id', $user->id)
            ->whereIn('status', ['PENDING_PAYMENT','ACTIVE','RETURN_REQUESTED'])->get();
        $activeSlots = $active->sum(fn($r)=> $r->unit->isSculptureDoubleSlot()?2:1);

        $cartUnits = Unit::whereIn('id', $cartIds)->get();
        $cartSlots = $cartUnits->sum(fn($u) => $u->isSculptureDoubleSlot() ? 2 : 1);
        $incomingSlots = $unit->isSculptureDoubleSlot() ? 2 : 1;

        if ($activeSlots + $cartSlots + $incomingSlots > 2) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Melebihi batas maksimal 2 unit di keranjang.',
                'redirect' => true,
            ], 422);
        }

        if ($unit->requiresDeposit()) {
            $hasCleanHistory = Rental::where('user_id',$user->id)
                ->where('status','RETURNED')
                ->whereDoesntHave('penalties', fn($q)=>$q->whereIn('kind',['DAMAGE','LATE']))
                ->exists();
            if (!$hasCleanHistory) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Belum memenuhi syarat menyewa unit 60s/70s.',
                ], 422);
            }
        }

        $cartIds[] = $unit->id;
        $this->saveCartUnitIds($request, $cartIds);

        return response()->json([
            'status'   => 'added',
            'message'  => "{$unit->name} masuk ke keranjang.",
        ]);
    }

    public function removeFromCart(Request $request, Unit $unit)
    {
        $this->ensureNotAdmin($request);

        $this->removeUnitFromCart($request, $unit->id);

        return redirect()->route('cart')->with([
            'toast' => "{$unit->name} dihapus dari keranjang.",
        ]);
    }

    public function purchases(Request $request)
    {
        $purchases = Rental::with(['unit.category','purchase'])
            ->where('user_id',$request->user()->id)
            ->where('status','PURCHASED')
            ->latest()->get();

        return view('user.purchases', compact('purchases'));
    }


    private function authorizeRentalOwner(Request $request, Rental $rental): void
    {
        abort_unless($rental->user_id === $request->user()->id || $request->user()->is_admin, 403);
    }

    private function getCartUnitIds(Request $request): array
    {
        return array_values(array_unique($request->session()->get('cart_units', [])));
    }

    private function saveCartUnitIds(Request $request, array $ids): void
    {
        $request->session()->put('cart_units', array_values(array_unique($ids)));
    }

    private function removeUnitFromCart(Request $request, int $unitId): void
    {
        $ids = $this->getCartUnitIds($request);
        $filtered = array_values(array_filter($ids, fn($id) => (int) $id !== $unitId));
        $this->saveCartUnitIds($request, $filtered);
    }

    private function ensureNotAdmin(Request $request): void
    {
        abort_if($request->user()?->is_admin, 403, 'Fitur ini khusus pengguna reguler.');
    }
}
