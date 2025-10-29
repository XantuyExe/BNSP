<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request) { return view('profile.edit', ['user'=>$request->user()]); }
    public function update(Request $request) {
        $user = $request->user();

        $data = $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone'    => ['nullable','string','max:30'],
            'address'  => ['nullable','string','max:255'],
            'password' => ['nullable','string','min:6','max:255'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return back()->with('status','Profil diperbarui.');
    }
}
