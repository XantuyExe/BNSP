<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnConfirmRequest;
use App\Models\{Rental, Penalty, Payment};
use App\Services\PricingService;
use Illuminate\Support\Facades\DB;

class ReturnAdminController extends Controller
{
    public function __construct(private PricingService $pricing) {}

    public function index()
    {
        $requests = Rental::with('user','unit.category')
            ->where('status', 'RETURN_REQUESTED')
            ->orderByDesc('return_requested_at')
            ->paginate(30);

        return view('admin.returns.index', compact('requests'));
    }

    public function form(\App\Models\Rental $rental) {
        $rental->load('user','unit.category');
        abort_if($rental->status !== 'RETURN_REQUESTED', 422, 'Pengembalian belum diajukan oleh user.');
        return view('admin.returns.confirm', compact('rental'));
    }


    public function confirm(ReturnConfirmRequest $request, Rental $rental)
    {
        abort_if(!in_array($rental->status, ['ACTIVE','RETURN_REQUESTED']), 422, 'Status rental tidak valid.');

        $rental->loadMissing('unit');

        $now      = now();
        $lateFee  = $this->pricing->calcLateFee($rental, $now);

        $cleaningFee = (int) $request->input('cleaning_fee', 0);
        $damageFee   = (int) $request->input('damage_fee', 0);
        $note        = $request->input('condition_note'); // <- AMBIL DI LUAR CLOSURE

        DB::transaction(function () use ($rental, $now, $lateFee, $cleaningFee, $damageFee, $note) {
            // Catat penalties
            if ($lateFee > 0) {
                Penalty::create([
                    'rental_id' => $rental->id,
                    'kind'      => 'LATE',
                    'amount'    => $lateFee,
                    'reason'    => 'Terlambat',
                ]);
            }

            if ($cleaningFee > 0) {
                Penalty::create([
                    'rental_id' => $rental->id,
                    'kind'      => 'CLEANING',
                    'amount'    => $cleaningFee,
                    'reason'    => 'Cleaning fee',
                ]);
            }

            if ($damageFee > 0) {
                Penalty::create([
                    'rental_id' => $rental->id,
                    'kind'      => 'DAMAGE',
                    'amount'    => $damageFee,
                    'reason'    => 'Damage fee',
                ]);
            }

            $totalPenalty  = $lateFee + $cleaningFee + $damageFee;
            $depositHeld   = (int) $rental->deposit_paid;
            $depositCoverage = min($totalPenalty, $depositHeld);
            $cashDue         = $totalPenalty - $depositCoverage;
            $depositRefund   = max(0, $depositHeld - $totalPenalty);

            if ($depositCoverage > 0) {
                Payment::create([
                    'rental_id' => $rental->id,
                    'type'      => 'PENALTY',
                    'amount'    => $depositCoverage,
                    'method'    => 'DEPOSIT',
                    'paid_at'   => now(),
                    'ref_code'  => 'DEPOSIT_DEDUCTION',
                ]);
            }

            if ($cashDue > 0) {
                Payment::create([
                    'rental_id' => $rental->id,
                    'type'      => 'PENALTY',
                    'amount'    => $cashDue,
                    'method'    => 'CASH',
                    'paid_at'   => now(),
                ]);
            }

            $rental->returnRecord()->create([
                'admin_id'        => auth()->id(),
                'return_checked_at' => $now,
                'cleaning_fee'    => $cleaningFee,
                'damage_fee'      => $damageFee,
                'deposit_refund'  => $depositRefund,
                'condition_note'  => $note, // <- PAKAI VARIABEL YANG DI-PASS KE CLOSURE
            ]);

            // Kembali tersedia
            $rental->unit()->update(['is_available' => true, 'is_sold' => false]);
            $rental->update([
                'status'            => 'RETURNED',
                'rental_end_actual' => $now,
                'return_requested_at' => null,
            ]);
        });

        return redirect()->to(route('adminManage.dashboard').'#riwayat-sewa')->with([
            'status' => 'Pengembalian dikonfirmasi.',
            'toast'  => 'Pengembalian dikonfirmasi.',
        ]);
    }
}
