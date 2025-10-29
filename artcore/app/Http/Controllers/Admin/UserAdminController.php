<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function index() {
        $users = User::orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    public function create() { return view('admin.users.create'); }
    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|string|max:120',
            'email'=>'required|email|max:255|unique:users,email',
            'phone'=>'nullable|string|max:30',
            'address'=>'nullable|string|max:255',
            'password'=>'required|string|min:6',
            'is_admin'=>'boolean'
        ]);
        $data['is_admin'] = $r->boolean('is_admin');
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('adminManage.users.index')->with('status','User dibuat.');
    }
    public function edit(User $user) {
        $history = $user->rentals()->with('unit')->orderByDesc('created_at')->limit(10)->get();
        return view('admin.users.edit', compact('user','history'));
    }
    public function update(Request $r, User $user) {
        $data = $r->validate([
            'name'=>'required|string|max:120',
            'email'=>"required|email|max:255|unique:users,email,{$user->id}",
            'phone'=>'nullable|string|max:30',
            'address'=>'nullable|string|max:255',
            'is_admin'=>'boolean',
            'password'=>'nullable|string|min:6'
        ]);
        $data['is_admin'] = $r->boolean('is_admin');
        if (!empty($data['password'])) $data['password']=bcrypt($data['password']); else unset($data['password']);
        $user->update($data); return back()->with('status','User diupdate.');
    }
    public function destroy(User $user) { $user->delete(); return back()->with('status','User dihapus.'); }
}
