@extends('layouts.app')
@section('title','Kategori')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Kategori</h1>
    <a href="{{ route('adminManage.categories.create') }}" class="btn btn-primary">Tambah</a>
  </div>
  <div class="card overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-brand-nav/40"><tr><th class="p-2 text-left">Nama</th><th class="p-2">Aksi</th></tr></thead>
      <tbody>
        @foreach($categories as $c)
          <tr class="border-t">
            <td class="p-2">{{ $c->name }}</td>
            <td class="p-2">
              <a href="{{ route('adminManage.categories.edit',$c) }}" class="underline">Edit</a>
              <form method="POST" action="{{ route('adminManage.categories.destroy',$c) }}" class="inline">@csrf @method('DELETE')
                <button class="text-rose-400 ml-2">Hapus</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
