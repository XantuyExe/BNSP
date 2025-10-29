@extends('layouts.app')
@section('title','Katalog')
@section('content')
  <div class="card p-4 mb-4">
    <form method="GET" action="{{ route('units.index') }}" data-scroll-anchor="#katalog" class="grid sm:grid-cols-5 gap-3">
      <input type="text" name="s" value="{{ request('s') }}" placeholder="Cari nama/kode" class="sm:col-span-2 border rounded-lg px-3 py-2 bg-white">
      <select name="category" class="border rounded-lg px-3 py-2 bg-white">
        <option value="">Kategori</option>
        @foreach(['PAINTING','SCULPTURE_3D','VINTAGE_FURNITURE'] as $k)
          <option @selected(request('category')===$k)>{{ $k }}</option>
        @endforeach
      </select>
      <select name="vintage" class="border rounded-lg px-3 py-2 bg-white">
        <option value="">Vintage</option>
        @foreach(['60s','70s','80s','90s'] as $v)<option @selected(request('vintage')===$v)>{{ $v }}</option>@endforeach
      </select>
      <div class="flex items-center sm:justify-end">
        <button class="btn btn-ghost w-full sm:w-auto">Filter</button>
      </div>
    </form>
  </div>
  <div id="katalog">
    @include('units._grid', ['units'=>$units ?? collect()])
  </div>
@endsection
