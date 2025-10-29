@if($units->count())
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($units as $u)
      @php($canAddToCart = auth()->check() && !auth()->user()->is_admin)
      @if($canAddToCart)
        <a href="{{ route('cart') }}"
           class="card p-3 hover:shadow-lg transition text-sm js-add-to-cart"
           data-unit-id="{{ $u->id }}"
           data-unit-name="{{ $u->name }}"
           data-cart-url="{{ route('cart') }}"
        >
      @else
        <div class="card p-3 hover:shadow-lg transition text-sm opacity-85 cursor-default select-none">
      @endif
        <div class="aspect-[3/2] w-full rounded-lg border border-dashed border-white/30 bg-brand-nav/40 flex items-center justify-center mb-2 overflow-hidden">
          @if(!empty($u->images))
            <img src="{{ asset('storage/'.$u->images[0]) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
          @else
            <span class="text-xs text-brand-text/60">Frame Gambar</span>
          @endif
        </div>
        <div class="flex items-start justify-between gap-3">
          <div class="pr-2">
            <div class="font-semibold text-brand-text text-sm truncate">{{ $u->name }}</div>
            <div class="text-[11px] text-brand-text/70 mt-0.5">{{ $u->category->name ?? '-' }} &middot; {{ $u->vintage }}</div>
          </div>
          <div class="text-right text-xs font-semibold text-brand-text whitespace-nowrap">Rp {{ number_format($u->rent_price_5d,0,',','.') }}</div>
        </div>
        <div class="mt-2 flex flex-wrap gap-2">
          @if(in_array($u->vintage,['60s','70s']))
            <span class="badge border-yellow-300/50 bg-yellow-200/10 text-yellow-200">Deposit 30%</span>
          @endif
          @if(($u->category->name ?? '')==='SCULPTURE_3D')
            <span class="badge border-brand-accent/40 bg-brand-accent/10 text-brand-text">2 slot</span>
          @endif
          <span class="badge {{ $u->is_sold ? 'border-amber-400/40 bg-amber-400/10 text-amber-200' : ($u->is_available ? 'border-emerald-400/40 bg-emerald-400/10 text-emerald-200' : 'border-rose-400/40 bg-rose-400/10 text-rose-200') }}">
            {{ $u->is_sold ? 'SOLD' : ($u->is_available ? 'Tersedia' : 'Tidak Tersedia') }}
          </span>
        </div>
      @if($canAddToCart)
        </a>
      @else
        </div>
      @endif
    @endforeach
  </div>
  @if(method_exists($units,'links')) <div class="mt-6">{{ $units->links() }}</div> @endif
@else
  @include('units.empty')
@endif
