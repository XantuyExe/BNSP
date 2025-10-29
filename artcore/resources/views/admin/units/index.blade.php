@extends('layouts.app')
@section('title','Manajemen Unit')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Unit</h1>
    <a href="{{ route('adminManage.units.create') }}" class="btn btn-primary">Tambah</a>
  </div>
  <div class="card overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-brand-teal/20">
        <tr>
          <th class="p-2 text-left">Nama</th>
          <th class="p-2 text-left">Kategori</th>
          <th class="p-2">Vintage</th>
          <th class="p-2">Sewa 5d</th>
          <th class="p-2">Status</th>
          <th class="p-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($units as $u)
          @php
            $statusLabel = $u->is_sold ? 'SOLD' : ($u->is_available ? 'Tersedia' : 'Tidak Tersedia');
          @endphp
          <tr class="border-t">
            <td class="p-2">{{ $u->name }}</td>
            <td class="p-2">{{ $u->category->name }}</td>
            <td class="p-2 text-center">{{ $u->vintage }}</td>
            <td class="p-2 text-right">Rp {{ number_format($u->rent_price_5d,0,',','.') }}</td>
            <td class="p-2 text-center">{{ $statusLabel }}</td>
            <td class="p-2 flex items-center gap-2">
              <a href="{{ route('adminManage.units.edit',$u) }}" class="underline">Edit</a>
              <form method="POST" action="{{ route('adminManage.units.destroy',$u) }}" class="inline">@csrf @method('DELETE')
                <button class="text-rose-600 ml-2">Hapus</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $units->links() }}</div>
@endsection
