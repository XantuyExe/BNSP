@extends('layouts.app')
@section('title','Tambah User')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Tambah User</h1>
  <form method="POST" action="{{ route('adminManage.users.store') }}" class="card p-6 max-w-xl space-y-4">
    @csrf
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="block text-sm text-brand-text/80 mb-1">Nama</label><input name="name" class="brand-input" required></div>
      <div><label class="block text-sm text-brand-text/80 mb-1">Email</label><input name="email" type="email" class="brand-input" required></div>
      <div><label class="block text-sm text-brand-text/80 mb-1">HP</label><input name="phone" class="brand-input" placeholder="Opsional"></div>
      <div><label class="block text-sm text-brand-text/80 mb-1">Password</label><input name="password" type="password" class="brand-input" required></div>
      <div class="md:col-span-2">
        <label class="block text-sm text-brand-text/80 mb-1">Alamat</label>
        <textarea name="address" rows="3" class="brand-input" placeholder="Opsional"></textarea>
      </div>
      <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-brand-text/80"><input type="checkbox" name="is_admin" value="1"> Admin</label>
      </div>
    </div>
    <button class="btn btn-primary w-full md:w-auto">Simpan</button>
  </form>
@endsection
