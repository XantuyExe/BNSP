<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreUnitRequest, UpdateUnitRequest};
use App\Models\{Unit, Category};

class UnitAdminController extends Controller
{
    public function index() {
        $units = Unit::with('category')
            ->statusOrdering()
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.units.index', compact('units'));
    }
    public function create() {
        $categories = Category::orderBy('name')->get();
        return view('admin.units.create', compact('categories'));
    }
    public function store(StoreUnitRequest $request) {
        $data = $request->validated();
        $images = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('units','public'); // storage/app/public/units/...
                    $images[] = $path;
                }
            }
        }
        $data['images'] = $images;
        $data['is_available'] = $request->boolean('is_available', true) && !$request->boolean('is_sold');
        $data['is_sold'] = $request->boolean('is_sold');
        Unit::create($data);

        return redirect()->route('adminManage.units.index')->with('status','Unit dibuat.');
    }
    public function edit(Unit $unit) {
        $categories = Category::orderBy('name')->get();
        return view('admin.units.edit', compact('unit','categories'));
    }
    public function update(UpdateUnitRequest $request, Unit $unit) {
    $data = $request->validated();
    $images = $unit->images ?? [];

    // hapus gambar yang dicentang
    foreach ((array) $request->input('remove_images', []) as $rm) {
        if (($idx = array_search($rm, $images)) !== false) {
            \Storage::disk('public')->delete($rm);
            unset($images[$idx]);
        }
    }

    // upload tambahan
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $file) {
            if ($file->isValid()) {
                $path = $file->store('units','public');
                $images[] = $path;
            }
        }
    }

    $data['images'] = array_values($images);
    $data['is_sold'] = $request->boolean('is_sold', $unit->is_sold);
    $data['is_available'] = $request->boolean('is_available', $unit->is_available) && !$data['is_sold'];
    $unit->update($data);

    return back()->with('status','Unit diperbarui.');
    }   
    public function destroy(Unit $unit) {
        $unit->delete(); return back()->with('status','Unit dihapus.');
    }
}
