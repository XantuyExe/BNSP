@extends('layouts.app')
@section('title','Dashboard User')
@section('content')
  <div class="grid md:grid-cols-3 gap-6">
    <div class="card p-6 md:col-span-2 bg-gradient-to-r from-brand-primary/15 to-brand-teal/15">
      <h1 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}</h1>
      <p class="text-brand-ink/70 mt-1">Kelola sewa aktif dan profil Anda.</p>
      <div class="mt-4 flex gap-3">
        <a href="{{ route('rentals.index') }}" class="btn btn-primary">Unit Disewa</a>
        <a href="{{ route('profile.edit') }}" class="btn btn-ghost">Profil</a>
      </div>
    </div>
    <div class="card p-6">
      <h3 class="font-semibold mb-2">Pintasan</h3>
      <ul class="text-sm space-y-2">
        <li><a class="hover:underline" href="{{ route('units.index') }}">Cari Karya Seni</a></li>
        <li><a class="hover:underline" href="{{ route('rentals.index') }}">Lihat Sewa Aktif</a></li>
      </ul>
    </div>
  </div>
@endsection
