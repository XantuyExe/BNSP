@extends('layouts.app')
@section('title',$unit->name)
@section('content')
  <div class="grid md:grid-cols-2 gap-8">
    <div class="card p-4">
      <div class="aspect-[4/3] w-full rounded-xl2 border border-dashed border-brand-ink/20 bg-white/60 flex items-center justify-center overflow-hidden">
        @if(!empty($unit->images))
          <img src="{{ asset('storage/'.$unit->images[0]) }}" alt="{{ $unit->name }}" class="w-full h-full object-cover">
        @else
          <span class="text-xs text-brand-ink/50">Belum ada foto.</span>
        @endif
      </div>
      @if(!empty($unit->images) && count($unit->images) > 1)
        <div class="grid grid-cols-4 gap-2 mt-3">
          @foreach(array_slice($unit->images,1) as $img)
            <div class="h-16 rounded-lg overflow-hidden border border-brand-nav/40">
              <img src="{{ asset('storage/'.$img) }}" alt="{{ $unit->name }}" class="w-full h-full object-cover">
            </div>
          @endforeach
        </div>
      @endif
    </div>
    <div class="card p-6">
      <h1 class="text-2xl font-bold">{{ $unit->name }}</h1>
      <div class="mt-1 text-sm text-brand-ink/60">{{ $unit->category->name }} &middot; {{ $unit->vintage }}</div>
      <div class="mt-4 space-y-1">
        <div>Harga Jual: <b>Rp {{ number_format($unit->sale_price,0,',','.') }}</b></div>
        <div>Sewa 5 Hari: <b>Rp {{ number_format($unit->rent_price_5d,0,',','.') }}</b></div>
        @if(in_array($unit->vintage,['60s','70s']))
          <div class="text-xs text-yellow-700">Deposit risiko 30% saat checkout.</div>
        @endif
        @if($unit->isSculptureDoubleSlot())
          <div class="text-xs text-brand-ink/70">Kategori Sculpture/3D menggunakan 2 slot penyewaan.</div>
        @endif
      </div>
      <p class="mt-4 text-sm text-brand-ink/80">{{ $unit->description ?: 'Belum ada deskripsi.' }}</p>

      @auth
        <form method="POST" action="{{ route('rentals.store') }}" class="mt-6">@csrf
          <input type="hidden" name="unit_id" value="{{ $unit->id }}">
          <button class="btn btn-primary"
                  @disabled(!$unit->is_available || $unit->is_sold)>
            {{ $unit->is_sold ? 'SOLD' : ($unit->is_available ? 'Sewa' : 'Tidak Tersedia') }}
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-primary mt-6">Login untuk menyewa</a>
      @endauth
    </div>
  </div>
@endsection

