@extends('layouts.app')
@section('title','Konfirmasi Pengembalian')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Konfirmasi Pengembalian</h1>
  <div class="card p-6 mb-4">
    <div class="font-semibold text-lg">{{ $rental->unit->name }}</div>
    <div class="text-sm text-brand-text/70">{{ $rental->user->name }} &middot; Mulai {{ $rental->rental_start?->format('d M Y') }} &middot; Jatuh Tempo {{ $rental->rental_end_plan?->format('d M Y') }}</div>
    <div class="text-xs text-brand-text/60 mt-2">Deposit dibayar: Rp {{ number_format($rental->deposit_paid,0,',','.') }} &middot; Biaya sewa: Rp {{ number_format($rental->rent_fee_paid,0,',','.') }}</div>
  </div>
  <form method="POST" action="{{ route('adminManage.returns.confirm',$rental) }}" class="card p-6 max-w-xl space-y-4">
    @csrf
    <p class="text-sm text-brand-text/70">Denda keterlambatan otomatis 10% dari biaya sewa dan akan dipotong dari deposit sebelum dikembalikan.</p>
    <div>
      <label class="block text-sm mb-1">Denda Pencucian (Rp)</label>
      <input name="cleaning_fee" type="number" class="border rounded-lg px-3 py-2 w-full" placeholder="0" min="0">
    </div>
    <div>
      <label class="block text-sm mb-1">Denda Kerusakan (Rp)</label>
      <input name="damage_fee" type="number" class="border rounded-lg px-3 py-2 w-full" placeholder="0" min="0">
    </div>
    <div>
      <label class="block text-sm mb-1">Catatan Kondisi</label>
      <textarea name="condition_note" class="border rounded-lg px-3 py-2 w-full" rows="3" placeholder="Catat keadaan unit saat kembali"></textarea>
    </div>
    <button class="btn btn-primary">Konfirmasi Pengembalian</button>
  </form>
@endsection

