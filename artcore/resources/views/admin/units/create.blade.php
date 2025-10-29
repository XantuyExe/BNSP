@extends('layouts.app')
@section('title','Tambah Unit')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Tambah Unit</h1>
  <form method="POST" action="{{ route('adminManage.units.store') }}" enctype="multipart/form-data"
        class="card p-6 grid md:grid-cols-2 gap-4">
    @csrf
    <div>
      <label class="block text-sm">Nama</label>
      <input name="name" class="brand-input" required>
    </div>
    <div>
      <label class="block text-sm">Kode</label>
      <input name="code" class="brand-input" required>
    </div>
    <div>
      <label class="block text-sm">Kategori</label>
      <select name="category_id" class="border rounded-lg px-3 py-2 w-full">
        @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Vintage</label>
      <select name="vintage" class="border rounded-lg px-3 py-2 w-full">
        @foreach(['60s','70s','80s','90s'] as $v)<option>{{ $v }}</option>@endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Harga Jual</label>
      <input type="number" name="sale_price" class="brand-input" required>
    </div>
    <div>
      <label class="block text-sm">Harga Sewa 5 Hari</label>
      <input type="number" name="rent_price_5d" class="brand-input" required>
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm">Deskripsi</label>
      <textarea name="description" class="brand-input"></textarea>
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm">Foto Unit (boleh lebih dari satu)</label>
      <input type="file" name="photos[]" multiple accept="image/*"
             class="block w-full text-sm file:mr-3 file:py-2 file:px-3 file:border file:rounded-lg file:bg-brand-nav file:text-brand-text">
      <p class="text-xs text-brand-text/70 mt-1">Boleh kosong. Gambar bisa ditambah/ubah di halaman Edit.</p>
    </div>

    <div class="md:col-span-2 flex flex-wrap gap-6 items-center">
      <label class="inline-flex items-center gap-2 text-sm text-brand-text/80">
        <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
        Tersedia untuk disewa
      </label>
      <label class="inline-flex items-center gap-2 text-sm text-brand-text/80">
        <input type="checkbox" name="is_sold" value="1" {{ old('is_sold') ? 'checked' : '' }}>
        Tandai sebagai SOLD
      </label>
    </div>

    <div class="md:col-span-2">
      <button class="btn btn-primary">Simpan</button>
    </div>
  </form>
@endsection
