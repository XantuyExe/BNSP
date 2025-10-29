@extends('layouts.app')
@section('title','Profil')
@section('content')
  <div class="grid gap-6 lg:grid-cols-2">
    <section class="card p-6 space-y-4">
      <header>
        <h1 class="text-xl font-semibold text-brand-text">Profil Saya</h1>
        <p class="text-sm text-brand-text/70 mt-1">Periksa informasi akun yang digunakan untuk mengakses Artcore.</p>
      </header>
      <dl class="space-y-4 text-sm">
        <div>
          <dt class="text-brand-text/60 uppercase tracking-wide text-xs">Nama</dt>
          <dd class="text-brand-text text-base">{{ $user->name ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-brand-text/60 uppercase tracking-wide text-xs">Email</dt>
          <dd class="text-brand-text text-base break-all">{{ $user->email }}</dd>
        </div>
        <div>
          <dt class="text-brand-text/60 uppercase tracking-wide text-xs">Password</dt>
          <dd class="text-brand-text font-mono">••••••••</dd>
          <p class="text-xs text-brand-text/60 mt-1">Demi keamanan, password tidak ditampilkan. Gunakan formulir untuk memperbarui.</p>
        </div>
        <div>
          <dt class="text-brand-text/60 uppercase tracking-wide text-xs">Nomor HP</dt>
          <dd class="text-brand-text text-base">{{ $user->phone ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-brand-text/60 uppercase tracking-wide text-xs">Alamat</dt>
          <dd class="text-brand-text text-base whitespace-pre-line">{{ $user->address ?: '—' }}</dd>
        </div>
      </dl>
    </section>

    <section class="card p-6">
      <h2 class="text-lg font-semibold text-brand-text mb-4">Perbarui Informasi</h2>
      <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf @method('PATCH')
        <div>
          <label class="block text-sm text-brand-text/80 mb-1" for="name">Nama</label>
          <input id="name" name="name" value="{{ old('name',$user->name) }}" class="brand-input" required>
        </div>
        <div>
          <label class="block text-sm text-brand-text/80 mb-1" for="email">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email',$user->email) }}" class="brand-input" required>
        </div>
        <div>
          <label class="block text-sm text-brand-text/80 mb-1" for="phone">Nomor HP</label>
          <input id="phone" name="phone" value="{{ old('phone',$user->phone) }}" class="brand-input" placeholder="Opsional">
        </div>
        <div>
          <label class="block text-sm text-brand-text/80 mb-1" for="address">Alamat</label>
          <textarea id="address" name="address" rows="3" class="brand-input" placeholder="Opsional">{{ old('address',$user->address) }}</textarea>
        </div>
        <div>
          <label class="block text-sm text-brand-text/80 mb-1" for="password">Password Baru</label>
          <input id="password" type="password" name="password" class="brand-input" placeholder="Isi jika ingin mengganti">
        </div>
        <button class="btn btn-primary w-full">Simpan Perubahan</button>
      </form>
    </section>
  </div>
@endsection
