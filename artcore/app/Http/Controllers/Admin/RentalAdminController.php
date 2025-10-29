<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalAdminController extends Controller
{
    public function index(Request $r)
    {
        $q = Rental::with('user','unit.category')
            ->whereIn('status', ['ACTIVE','RETURN_REQUESTED'])
            ->orderByDesc('created_at');

        if ($r->filled('status')) {
            $q->where('status', $r->string('status'));
        }

        if ($r->filled('user')) {
            $keyword = $r->string('user');
            $q->whereHas('user', fn($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"));
        }

        if ($r->filled('unit')) {
            $keyword = $r->string('unit');
            $q->whereHas('unit', fn($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%"));
        }

        $rentals = $q->paginate(30)->withQueryString();

        return view('admin.rentals.index', [
            'rentals' => $rentals,
            'filters' => [
                'status' => $r->string('status'),
                'user'   => $r->string('user'),
                'unit'   => $r->string('unit'),
            ],
        ]);
    }

    public function show(Rental $rental)
    {
        $rental->load('user','unit.category','payments','penalties','returnRecord','purchase');
        return view('admin.rentals.show', compact('rental'));
    }

    public function history(Request $r)
    {
        $q = Rental::with('user','unit.category')->orderByDesc('created_at');

        if ($r->filled('status')) {
            $q->where('status', $r->string('status'));
        }

        if ($r->filled('user')) {
            $keyword = $r->string('user');
            $q->whereHas('user', fn($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"));
        }

        if ($r->filled('unit')) {
            $keyword = $r->string('unit');
            $q->whereHas('unit', fn($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%"));
        }

        if ($r->filled('from')) {
            $q->whereDate('rental_start', '>=', $r->date('from'));
        }

        if ($r->filled('to')) {
            $q->whereDate('rental_start', '<=', $r->date('to'));
        }

        $rentals = $q->paginate(40)->withQueryString();

        return view('admin.rentals.history', [
            'rentals' => $rentals,
            'filters' => [
                'status' => $r->string('status'),
                'user'   => $r->string('user'),
                'unit'   => $r->string('unit'),
                'from'   => $r->string('from'),
                'to'     => $r->string('to'),
            ],
        ]);
    }

    public function exportHistory(Request $r)
    {
        $q = Rental::with('user','unit.category')->orderByDesc('created_at');

        if ($r->filled('status')) {
            $q->where('status', $r->string('status'));
        }
        if ($r->filled('user')) {
            $keyword = $r->string('user');
            $q->whereHas('user', fn($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"));
        }
        if ($r->filled('unit')) {
            $keyword = $r->string('unit');
            $q->whereHas('unit', fn($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%"));
        }
        if ($r->filled('from')) {
            $q->whereDate('rental_start', '>=', $r->date('from'));
        }
        if ($r->filled('to')) {
            $q->whereDate('rental_start', '<=', $r->date('to'));
        }

        $rentals = $q->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="riwayat-sewa.csv"',
        ];

        $callback = function () use ($rentals) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['User', 'Email', 'Unit', 'Kategori', 'Status', 'Mulai', 'Jatuh Tempo', 'Selesai', 'Biaya Sewa', 'Deposit']);

            foreach ($rentals as $rental) {
                fputcsv($handle, [
                    $rental->user->name ?? '-',
                    $rental->user->email ?? '-',
                    $rental->unit->name ?? '-',
                    $rental->unit->category->name ?? '-',
                    $rental->status,
                    optional($rental->rental_start)->format('Y-m-d H:i'),
                    optional($rental->rental_end_plan)->format('Y-m-d H:i'),
                    optional($rental->rental_end_actual)->format('Y-m-d H:i'),
                    $rental->rent_fee_paid,
                    $rental->deposit_paid,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'riwayat-sewa.csv', $headers);
    }
}
