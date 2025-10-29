@extends('layouts.app')
@section('title','Edit User')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Edit User - {{ $user->name }}</h1>
  <div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('adminManage.users.update', $user) }}" class="card p-6 space-y-4">
      @csrf @method('PATCH')
      <div>
        <label class="block text-sm text-brand-text/80 mb-1">Nama</label>
        <input name="name" value="{{ old('name', $user->name) }}" class="brand-input" required>
      </div>
      <div>
        <label class="block text-sm text-brand-text/80 mb-1">Email</label>
        <input name="email" type="email" value="{{ old('email', $user->email) }}" class="brand-input" required>
      </div>
      <div>
        <label class="block text-sm text-brand-text/80 mb-1">HP</label>
        <input name="phone" value="{{ old('phone', $user->phone) }}" class="brand-input" placeholder="Opsional">
      </div>
      <div>
        <label class="block text-sm text-brand-text/80 mb-1">Alamat</label>
        <textarea name="address" rows="3" class="brand-input" placeholder="Opsional">{{ old('address', $user->address) }}</textarea>
      </div>
      <div>
        <label class="block text-sm text-brand-text/80 mb-1">Password (opsional)</label>
        <input name="password" type="password" class="brand-input" placeholder="Isi untuk reset">
      </div>
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_admin" value="1" @checked($user->is_admin)>
        Admin</label>
      <button class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <div class="card p-6">
      <h2 class="font-semibold mb-3">Riwayat Sewa Terbaru</h2>
      <div class="space-y-3 text-sm">
        @forelse($history as $r)
          <div class="border border-brand-nav/40 rounded-lg p-3">
            <div class="font-semibold">{{ $r->unit->name }}</div>
            <div class="text-xs text-brand-text/70">Status: {{ $r->status }} &middot; Mulai {{ $r->rental_start?->format('d M Y') }}</div>
            @if($r->status === 'RETURNED')
              <div class="text-xs text-brand-text/60">Selesai {{ $r->rental_end_actual?->format('d M Y') }}</div>
            @endif
          </div>
        @empty
          <div class="text-brand-text/60">Belum ada riwayat sewa.</div>
        @endforelse
      </div>
    </div>
  </div>
@endsection

