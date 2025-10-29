<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryAdminController extends Controller
{
    public function index() { $categories = Category::orderBy('name')->get(); return view('admin.categories.index', compact('categories')); }
    public function create() { return view('admin.categories.create'); }
    public function store(Request $r) {
        $data = $r->validate(['name'=>'required|string|max:100|unique:categories,name']);
        Category::create($data); return redirect()->route('adminManage.categories.index')->with('status','Kategori dibuat.');
    }
    public function edit(Category $category) { return view('admin.categories.edit', compact('category')); }
    public function update(Request $r, Category $category) {
        $data = $r->validate(['name'=>"required|string|max:100|unique:categories,name,{$category->id}"]);
        $category->update($data); return back()->with('status','Kategori diupdate.');
    }
    public function destroy(Category $category) { $category->delete(); return back()->with('status','Kategori dihapus.'); }
}
