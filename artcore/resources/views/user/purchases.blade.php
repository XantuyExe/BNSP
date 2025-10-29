@extends('layouts.app')
@section('title','Riwayat Pembelian')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Riwayat Pembelian</h1>

  @forelse($purchases as $r)
    <div class="card p-4 mb-3">
      <div class="flex items-start justify-between gap-4">
        <div>
          <div class="font-semibold">{{ $r->unit->name }}</div>
          <div class="text-xs text-brand-text/70">{{ $r->unit->category->name }} &middot; {{ $r->unit->vintage }}</div>
          <div class="text-sm mt-1">Dibeli (TPO): {{ $r->purchase?->decided_at?->format('d M Y') ?? '-' }}</div>
        </div>
        <div class="text-right">
          <div class="text-sm">Harga Akhir</div>
          <div class="font-semibold">Rp {{ number_format($r->purchase?->final_price ?? 0,0,',','.') }}</div>
        </div>
      </div>
    </div>
  @empty
    <div class="card p-10 text-center">
      <div class="text-3xl mb-3">[riwayat]</div>
      <h2 class="text-xl font-semibold mb-2">Belum Ada Pembelian</h2>
      <p class="text-sm text-brand-text/70">Mulai dengan menyewa dan memanfaatkan Trial-to-Own.</p>
      <a href="{{ route('units.index') }}" class="btn btn-primary mt-5">Lihat Katalog</a>
    </div>
  @endforelse
@endsection

