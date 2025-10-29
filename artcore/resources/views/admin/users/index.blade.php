@extends('layouts.app')
@section('title','Users')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Users</h1>
    <a href="{{ route('adminManage.users.create') }}" class="btn btn-primary">Tambah</a>
  </div>
  <div class="card overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-brand-nav/40"><tr>
        <th class="p-2 text-left">Nama</th><th class="p-2">Email</th><th class="p-2">Admin</th><th class="p-2">Aksi</th>
      </tr></thead>
      <tbody>
        @foreach($users as $u)
          <tr class="border-t">
            <td class="p-2">{{ $u->name }}</td>
            <td class="p-2">{{ $u->email }}</td>
            <td class="p-2 text-center">{{ $u->is_admin?'Ya':'Tidak' }}</td>
            <td class="p-2">
              <a href="{{ route('adminManage.users.edit',$u) }}" class="underline">Edit</a>
              <form method="POST" action="{{ route('adminManage.users.destroy',$u) }}" class="inline">@csrf @method('DELETE')
                <button class="text-rose-400 ml-2">Hapus</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $users->links() }}</div>
@endsection
