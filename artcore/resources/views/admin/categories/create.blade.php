@extends('layouts.app')
@section('title','Tambah Kategori')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Tambah Kategori</h1>
  <form method="POST" action="{{ route('adminManage.categories.store') }}" class="card p-6 max-w-lg">
    @csrf
    <label class="block text-sm mb-1">Nama</label>
    <input name="name" class="brand-input mb-4">
    <button class="btn btn-primary">Simpan</button>
  </form>
@endsection
