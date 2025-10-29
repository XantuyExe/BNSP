@extends('layouts.app')
@section('title','Edit Kategori')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Edit Kategori</h1>
  <form method="POST" action="{{ route('adminManage.categories.update',$category) }}" class="card p-6 max-w-lg">
    @csrf @method('PATCH')
    <label class="block text-sm mb-1">Nama</label>
    <input name="name" value="{{ old('name',$category->name) }}" class="brand-input mb-4">
    <button class="btn btn-primary">Simpan</button>
  </form>
@endsection
