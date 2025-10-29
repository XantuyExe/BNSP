<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Unit, Category};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $availableQuery = Unit::query()
            ->with('category')
            ->where('is_available', true)
            ->where('is_sold', false);
        $filteredQuery  = clone $availableQuery;

        if ($request->filled('category')) {
            $category = Category::where('name', $request->string('category'))->first();
            if ($category) {
                $filteredQuery->where('category_id', $category->id);
            }
        }
        if ($request->filled('vintage')) {
            $filteredQuery->where('vintage', $request->string('vintage'));
        }
        if ($request->filled('s')) {
            $search = $request->string('s');
            $filteredQuery->where(function($x) use ($search) {
                $x->where('name','like',"%$search%")->orWhere('code','like',"%$search%");
            });
        }

        $units = $filteredQuery->orderBy('name')->paginate(12)->withQueryString();
        $highlights = (clone $availableQuery)->latest()->take(8)->get();

        return view('home', [
            'units'      => $units,
            'highlights' => $highlights,
        ]);
    }
}
