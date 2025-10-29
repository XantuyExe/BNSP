@extends('layouts.app')
@section('title','Edit Unit')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Edit Unit - {{ $unit->name }}</h1>
  <form method="POST" action="{{ route('adminManage.units.update',$unit) }}" enctype="multipart/form-data"
        class="card p-6 grid md:grid-cols-2 gap-4">
    @csrf @method('PATCH')
    <div>
      <label class="block text-sm">Nama</label>
      <input name="name" value="{{ old('name',$unit->name) }}" class="brand-input" required>
    </div>
    <div>
      <label class="block text-sm">Kode</label>
      <input name="code" value="{{ old('code',$unit->code) }}" class="brand-input" required>
    </div>
    <div>
      <label class="block text-sm">Kategori</label>
      <select name="category_id" class="border rounded-lg px-3 py-2 w-full">
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected($unit->category_id==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Vintage</label>
      <select name="vintage" class="border rounded-lg px-3 py-2 w-full">
        @foreach(['60s','70s','80s','90s'] as $v)<option @selected($unit->vintage==$v)>{{ $v }}</option>@endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Harga Jual</label>
      <input type="number" name="sale_price" value="{{ $unit->sale_price }}" class="brand-input" required>
    </div>
    <div>
      <label class="block text-sm">Harga Sewa 5 Hari</label>
      <input type="number" name="rent_price_5d" value="{{ $unit->rent_price_5d }}" class="brand-input" required>
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm">Deskripsi</label>
      <textarea name="description" class="brand-input">{{ $unit->description }}</textarea>
    </div>

    {{-- Gambar yang sudah ada --}}
    <div class="md:col-span-2">
      <label class="block font-semibold mb-2 text-sm">Gambar Saat Ini</label>
      @if($unit->images && count($unit->images))
        <div class="grid sm:grid-cols-3 md:grid-cols-4 gap-3">
          @foreach($unit->images as $img)
            <label class="block">
              <div class="aspect-[4/3] w-full rounded-lg overflow-hidden border border-brand-nav">
                <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover" alt="">
              </div>
              <div class="mt-1 text-xs flex items-center gap-2">
                <input type="checkbox" name="remove_images[]" value="{{ $img }}"> Hapus
              </div>
            </label>
          @endforeach
        </div>
      @else
        <div class="text-sm text-brand-text/70">Belum ada gambar.</div>
      @endif
    </div>

    {{-- Upload tambahan --}}
    <div class="md:col-span-2">
      <label class="block text-sm">Tambah Foto</label>
      <input type="file" name="photos[]" multiple accept="image/*"
             class="block w-full text-sm file:mr-3 file:py-2 file:px-3 file:border file:rounded-lg file:bg-brand-nav file:text-brand-text">
    </div>

    <div class="md:col-span-2 flex flex-wrap gap-6 items-center">
      <label class="inline-flex items-center gap-2 text-sm text-brand-text/80">
        <input type="checkbox" name="is_available" value="1" @checked(old('is_available',$unit->is_available))>
        Tersedia untuk disewa
      </label>
      <label class="inline-flex items-center gap-2 text-sm text-brand-text/80">
        <input type="checkbox" name="is_sold" value="1" @checked(old('is_sold',$unit->is_sold))>
        Tandai sebagai SOLD
      </label>
    </div>

    <div class="md:col-span-2">
      <button class="btn btn-primary">Simpan Perubahan</button>
    </div>
  </form>
@endsection

