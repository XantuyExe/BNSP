@extends('layouts.app')
@section('title','Detail Sewa')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Detail Sewa - {{ $rental->unit->name }}</h1>
  <div class="grid lg:grid-cols-2 gap-6">
    <div class="card p-6 space-y-2">
      <h2 class="font-semibold">Informasi Utama</h2>
      <p class="text-sm text-brand-text/80">User: <b>{{ $rental->user->name }}</b> ({{ $rental->user->email }})</p>
      <p class="text-sm text-brand-text/80">Status: <b>{{ $rental->status }}</b></p>
      <p class="text-sm text-brand-text/70">Mulai: {{ $rental->rental_start?->format('d M Y H:i') ?? '-' }}</p>
      <p class="text-sm text-brand-text/70">Jatuh Tempo: {{ $rental->rental_end_plan?->format('d M Y H:i') ?? '-' }}</p>
      <p class="text-sm text-brand-text/70">Selesai: {{ $rental->rental_end_actual?->format('d M Y H:i') ?? '-' }}</p>
      <p class="text-sm text-brand-text/70">Return Request: {{ $rental->return_requested_at?->format('d M Y H:i') ?? '-' }}</p>
      <div class="pt-3 text-sm text-brand-text/80">
        <div>Biaya sewa: Rp {{ number_format($rental->rent_fee_paid,0,',','.') }}</div>
        <div>Deposit: Rp {{ number_format($rental->deposit_paid,0,',','.') }} (wajib: Rp {{ number_format($rental->deposit_required,0,',','.') }})</div>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-semibold mb-3">Catatan Pengembalian</h2>
      @if($rental->returnRecord)
        <p class="text-sm text-brand-text/80">Diperiksa oleh: {{ $rental->returnRecord->admin->name ?? '-' }}</p>
        <p class="text-sm text-brand-text/80">Tanggal: {{ $rental->returnRecord->return_checked_at?->format('d M Y H:i') ?? '-' }}</p>
        <p class="text-sm text-brand-text/80">Cleaning Fee: Rp {{ number_format($rental->returnRecord->cleaning_fee,0,',','.') }}</p>
        <p class="text-sm text-brand-text/80">Damage Fee: Rp {{ number_format($rental->returnRecord->damage_fee,0,',','.') }}</p>
        <p class="text-sm text-brand-text/80">Deposit Refund: Rp {{ number_format($rental->returnRecord->deposit_refund,0,',','.') }}</p>
        <p class="text-sm text-brand-text/70 mt-2">Catatan: {{ $rental->returnRecord->condition_note ?? '-' }}</p>
      @else
        <p class="text-sm text-brand-text/60">Belum ada catatan pengembalian.</p>
      @endif
    </div>
  </div>

  <div class="grid lg:grid-cols-2 gap-6 mt-6">
    <div class="card p-6 overflow-hidden">
      <h2 class="font-semibold mb-3">Pembayaran</h2>
      <table class="w-full text-sm">
        <thead class="bg-brand-nav/40">
          <tr><th class="p-2 text-left">Jenis</th><th class="p-2 text-right">Jumlah</th><th class="p-2">Metode</th><th class="p-2">Tanggal</th></tr>
        </thead>
        <tbody>
          @forelse($rental->payments as $payment)
            <tr class="border-t">
              <td class="p-2">{{ $payment->type }}</td>
              <td class="p-2 text-right">Rp {{ number_format($payment->amount,0,',','.') }}</td>
              <td class="p-2 text-center">{{ $payment->method }}</td>
              <td class="p-2 text-center">{{ $payment->paid_at?->format('d M Y') ?? '-' }}</td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="4">Belum ada pembayaran.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card p-6 overflow-hidden">
      <h2 class="font-semibold mb-3">Penalti</h2>
      <table class="w-full text-sm">
        <thead class="bg-brand-nav/40">
          <tr><th class="p-2 text-left">Jenis</th><th class="p-2 text-right">Jumlah</th><th class="p-2 text-left">Alasan</th></tr>
        </thead>
        <tbody>
          @forelse($rental->penalties as $penalty)
            <tr class="border-t">
              <td class="p-2">{{ $penalty->kind }}</td>
              <td class="p-2 text-right">Rp {{ number_format($penalty->amount,0,',','.') }}</td>
              <td class="p-2">{{ $penalty->reason ?? '-' }}</td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="3">Tidak ada penalti.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

