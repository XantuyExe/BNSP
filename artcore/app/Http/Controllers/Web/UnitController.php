<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $q = Unit::with('category');

        if ($request->filled('s')) {
            $search = $request->string('s');
            $q->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $q->whereHas('category', fn($builder) => $builder->where('name', $request->string('category')));
        }

        if ($request->filled('vintage')) {
            $q->where('vintage', $request->string('vintage'));
        }

        $units = $q->statusOrdering()->paginate(18)->withQueryString();

        return view('units.index', [
            'units' => $units,
        ]);
    }
    public function show(Unit $unit) { $unit->load('category'); return view('units.show', compact('unit')); }
}
