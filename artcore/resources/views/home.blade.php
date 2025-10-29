@extends('layouts.app')
@section('title','ArtCore - Beranda')
@section('content')
  <!-- Hero -->
  <section class="grid md:grid-cols-2 gap-6 items-center mb-8">
    <div class="card p-8 bg-brand-card/50">
      <h1 class="text-3xl md:text-4xl font-bold leading-tight text-brand-text">Sewa & Miliki Karya Seni Vintage</h1>
      <p class="mt-3 text-brand-text/80">Lukisan, patung, dan perabot vintage dengan <b>Trial-to-Own 5 hari</b>.</p>
      <div class="mt-5 flex gap-3">
        <a href="{{ route('home') }}#katalog" class="btn btn-primary">Jelajahi Katalog</a>
        @guest <a href="{{ route('register') }}" class="btn btn-ghost">Daftar</a> @endguest
      </div>
    </div>

    <div class="card p-4">
      <form method="GET" action="{{ route('home') }}" data-scroll-anchor="#katalog" class="grid sm:grid-cols-3 gap-3">
        <input type="text" name="s" value="{{ request('s') }}" placeholder="Cari nama/kode"
               class="sm:col-span-3 border rounded-lg px-3 py-2 bg-white/80">
        <select name="category" class="border rounded-lg px-3 py-2 bg-white/80">
          <option value="">Semua Kategori</option>
          @foreach(['PAINTING'=>'Seni Lukis','SCULPTURE_3D'=>'Seni 3D/Patung','VINTAGE_FURNITURE'=>'Perabot Vintage'] as $k=>$v)
            <option value="{{ $k }}" @selected(request('category')===$k)>{{ $v }}</option>
          @endforeach
        </select>
        <select name="vintage" class="border rounded-lg px-3 py-2 bg-white/80">
          <option value="">Semua Vintage</option>
          @foreach(['60s','70s','80s','90s'] as $v)
            <option @selected(request('vintage')===$v)>{{ $v }}</option>
          @endforeach
        </select>
        <button class="btn btn-ghost">Filter</button>
      </form>
    </div>
  </section>

  {{-- Sorotan (carousel horizontal) --}}
  <section id="sorotan" class="mb-12">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-brand-text text-lg">Sorotan Terbaru</h2>
      @if($highlights->isNotEmpty())
        <div class="flex gap-2">
          <button type="button" class="carousel-btn" data-carousel-control data-target="highlight-carousel" data-direction="-1">&lsaquo;</button>
          <button type="button" class="carousel-btn" data-carousel-control data-target="highlight-carousel" data-direction="1">&rsaquo;</button>
        </div>
      @endif
    </div>
    @if($highlights->isEmpty())
      <div class="card p-6 text-brand-text/70">Unit belum tersedia. Admin dapat menambahkan dari dashboard.</div>
    @else
      @php($canAddToCart = auth()->check() && !auth()->user()->is_admin)
      <div class="relative">
        <div id="highlight-carousel" class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2">
          @foreach($highlights as $u)
            @if($canAddToCart)
              <a href="{{ route('cart') }}"
                 class="highlight-card card p-5 snap-start min-w-[18rem] sm:min-w-[20rem] lg:min-w-[22rem] js-add-to-cart"
                 data-unit-id="{{ $u->id }}"
                 data-unit-name="{{ $u->name }}"
                 data-cart-url="{{ route('cart') }}"
              >
            @else
              <div class="highlight-card card p-5 snap-start min-w-[18rem] sm:min-w-[20rem] lg:min-w-[22rem] opacity-85 cursor-default select-none">
            @endif
              <div class="aspect-[4/3] w-full rounded-xl overflow-hidden border border-white/20 bg-brand-nav/40 flex items-center justify-center mb-4">
                @if(!empty($u->images))
                  <img src="{{ asset('storage/'.$u->images[0]) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                @else
                  <span class="text-xs text-brand-text/60">Belum ada foto</span>
                @endif
              </div>
              <div class="font-semibold text-brand-text text-lg mb-1 truncate">{{ $u->name }}</div>
              <div class="text-xs text-brand-text/70">{{ $u->category->name ?? '-' }} &middot; {{ $u->vintage }}</div>
              <div class="mt-3 text-sm text-brand-text/90">Sewa 5 Hari: <b>Rp {{ number_format($u->rent_price_5d,0,',','.') }}</b></div>
            @if($canAddToCart)
              </a>
            @else
              </div>
            @endif
          @endforeach
        </div>
      </div>
    @endif
  </section>

  <!-- Grid katalog (tetap ada) -->
  <section id="katalog" class="pb-12">
    <div class="flex items-center justify-between mb-3">
      <h2 class="font-semibold text-brand-text text-lg">Katalog</h2>
      <span class="text-xs text-brand-text/60">Menampilkan {{ $units->total() }} unit</span>
    </div>
    @include('units._grid', ['units'=>$units])
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('[data-carousel-control]');
      buttons.forEach(button => {
        button.addEventListener('click', () => {
          const targetId = button.getAttribute('data-target');
          const direction = parseInt(button.getAttribute('data-direction'), 10);
          const container = document.getElementById(targetId);
          if (!container) return;
          const card = container.querySelector('.highlight-card');
          const scrollAmount = card ? card.offsetWidth + 24 : 320;
          container.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
        });
      });

    });
  </script>
@endsection

