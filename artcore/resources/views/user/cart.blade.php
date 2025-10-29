@extends('layouts.app')
@section('title','Keranjang')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Keranjang</h1>

  <div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-4">
      @foreach($items as $item)
        @php($unit = $item['unit'])
        <div class="card p-5">
          <div class="flex flex-col md:flex-row md:items-start gap-4">
            <div class="w-full md:w-44 h-32 rounded-lg border border-dashed border-white/30 bg-brand-nav/40 flex items-center justify-center overflow-hidden">
              @if(!empty($unit->images))
                <img src="{{ asset('storage/'.$unit->images[0]) }}" alt="{{ $unit->name }}" class="w-full h-full object-cover">
              @else
                <span class="text-xs text-brand-text/60">Belum ada foto</span>
              @endif
            </div>
            <div class="flex-1 space-y-2">
              <div>
                <div class="font-semibold text-lg text-brand-text">{{ $unit->name }}</div>
                <div class="text-xs text-brand-text/60">{{ $unit->category->name ?? '-' }} &middot; {{ $unit->vintage }}</div>
              </div>
              <p class="text-sm text-brand-text/80 leading-relaxed">
                {{ $unit->description ?: 'Belum ada deskripsi untuk unit ini.' }}
              </p>
              <div class="text-sm text-brand-text/80 space-y-1">
                <div>Sewa 5 hari: <b>Rp {{ number_format($item['rent'],0,',','.') }}</b></div>
                @if($item['deposit'] > 0)
                  <div>Deposit risiko 30%: <b>Rp {{ number_format($item['deposit'],0,',','.') }}</b></div>
                @endif
                <div>Subtotal: <b>Rp {{ number_format($item['subtotal'],0,',','.') }}</b></div>
                <div>Slot terpakai: <b>{{ $item['slots'] }}</b></div>
              </div>
              <div class="flex flex-wrap gap-3 pt-2">
                <form method="POST" action="{{ route('rentals.store') }}">
                  @csrf
                  <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                  <button class="btn btn-primary">Konfirmasi &amp; Mulai Sewa</button>
                </form>
                <form method="POST" action="{{ route('cart.remove', $unit) }}">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-ghost">Hapus dari Keranjang</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <aside class="card p-6 space-y-4">
      <div>
        <h2 class="font-semibold text-brand-text mb-1">Ringkasan</h2>
        <p class="text-xs text-brand-text/60">Pastikan slot tersisa mencukupi sebelum konfirmasi.</p>
      </div>
      <div class="text-sm space-y-2">
        <div class="flex justify-between"><span>Sewa 5 hari</span><span>Rp {{ number_format($totals['rent'],0,',','.') }}</span></div>
        <div class="flex justify-between"><span>Deposit</span><span>Rp {{ number_format($totals['deposit'],0,',','.') }}</span></div>
        <hr class="border-brand-nav my-2">
        <div class="flex justify-between font-semibold text-lg">
          <span>Total</span><span>Rp {{ number_format($totals['overall'],0,',','.') }}</span>
        </div>
      </div>
      <div class="text-xs text-brand-text/70 space-y-1">
        <div>Slot aktif: <b>{{ $activeSlots }}</b> / {{ $maxSlots }}</div>
        <div>Slot di keranjang: <b>{{ $totals['cartSlots'] }}</b></div>
        <div class="{{ ($activeSlots + $totals['cartSlots']) > $maxSlots ? 'text-amber-300' : '' }}">
          Total slot setelah checkout: <b>{{ $activeSlots + $totals['cartSlots'] }}</b> / {{ $maxSlots }}
        </div>
      </div>
      <p class="text-xs text-brand-text/70">
        Jatuh tempo otomatis 5 hari. Keterlambatan dikenakan denda 10% dari biaya sewa dan akan dipotong dari deposit ketika tersedia.
      </p>
    </aside>
  </div>

  <script>
    sessionStorage.removeItem('artcore.cart.redirect');
  </script>
@endsection

