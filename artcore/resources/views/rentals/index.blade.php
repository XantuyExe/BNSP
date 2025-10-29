@extends('layouts.app')
@section('title','Unit Disewa')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Unit Disewa</h1>
  @forelse($rentals as $r)
    <div class="card p-4 mb-3">
      <div class="flex items-start justify-between gap-4">
        <div>
          <div class="font-semibold">{{ $r->unit->name }}</div>
          <div class="text-xs text-brand-ink/60">{{ $r->unit->category->name }} &middot; {{ $r->unit->vintage }}</div>
          <div class="text-sm mt-2">Mulai Sewa: {{ $r->rental_start?->format('d M Y H:i') }}</div>
          <div class="text-sm">Jatuh Tempo: <b>{{ $r->rental_end_plan?->format('d M Y H:i') }}</b></div>
          <div class="text-xs text-amber-600 mt-1">Denda 10% dari biaya sewa jika melewati jatuh tempo.</div>
          @if($r->status === 'RETURN_REQUESTED')
            <div class="mt-2 text-xs text-brand-ink/70">Menunggu konfirmasi admin sejak {{ $r->return_requested_at?->format('d M Y H:i') }}.</div>
          @endif
        </div>
        <div class="flex flex-col gap-2 items-end">
          <span class="badge {{ $r->status === 'RETURN_REQUESTED' ? 'border-amber-300/60 bg-amber-300/10 text-amber-200' : 'border-emerald-300/60 bg-emerald-300/10 text-emerald-100' }}">
            {{ $r->status === 'RETURN_REQUESTED' ? 'Menunggu Konfirmasi' : 'Aktif' }}
          </span>
          @if(now()->diffInDays($r->rental_start) <= config('artcore.tpo_window_days') && $r->status === 'ACTIVE')
            <form method="POST" action="{{ route('rentals.purchase', $r) }}" class="mt-1">@csrf
              <button class="btn btn-primary">Beli Unit (TPO)</button>
            </form>
          @endif
          @if($r->status === 'ACTIVE')
            <form method="POST" action="{{ route('rentals.return-request', $r) }}" class="mt-1">
              @csrf
              <button class="btn btn-ghost">Ajukan Pengembalian</button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @empty
    <div class="card p-6 text-brand-ink/60">Belum ada sewa aktif.</div>
  @endforelse
@endsection

