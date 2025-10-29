<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Unit, Rental, Payment, Category};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'units_total'        => Unit::count(),
            'units_available'    => Unit::where('is_available', true)->where('is_sold', false)->count(),
            'rentals_active'     => Rental::whereIn('status', ['ACTIVE','RETURN_REQUESTED'])->count(),
            'deposits_held'      => Payment::where('type', 'DEPOSIT')->sum('amount'),
            'return_requests'    => Rental::where('status', 'RETURN_REQUESTED')->count(),
            'users_total'        => User::count(),
        ];

        $latestUnits = Unit::with('category')->statusOrdering()->orderByDesc('created_at')->limit(6)->get();
        $recentUsers = User::latest()->limit(6)->get();
        $categories  = Category::withCount('units')->orderBy('name')->limit(8)->get();
        $availableUnits = Unit::with('category')
            ->where('is_available', true)
            ->where('is_sold', false)
            ->latest()
            ->limit(5)
            ->get();
        $activeRentals = Rental::with('user','unit.category')
            ->where('status','ACTIVE')
            ->orderByDesc('rental_start')
            ->limit(5)
            ->get();
        $purchasedRentals = Rental::with('user','unit.category')
            ->where('status','PURCHASED')
            ->orderByDesc('rental_end_actual')
            ->limit(5)
            ->get();
        $returnRequests = Rental::with('user','unit.category')
            ->where('status','RETURN_REQUESTED')
            ->orderByDesc('return_requested_at')
            ->limit(6)
            ->get();
        $rentalHistory = Rental::with('user','unit.category')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats'           => $stats,
            'latestUnits'     => $latestUnits,
            'recentUsers'     => $recentUsers,
            'categories'      => $categories,
            'availableUnits'  => $availableUnits,
            'activeRentals'   => $activeRentals,
            'purchasedRentals'=> $purchasedRentals,
            'returnRequests'  => $returnRequests,
            'rentalHistory'   => $rentalHistory,
        ]);
    }
}
