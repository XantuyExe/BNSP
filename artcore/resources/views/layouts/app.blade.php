@php($user = auth()->user())
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','ArtCore')</title>
  <script>
    window.__toastQueue = window.__toastQueue || [];
    window.showToast = window.showToast || function(message, opts) {
      window.__toastQueue.push({ message, opts });
    };
  </script>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
  <!-- Navbar -->
  <header data-main-header class="site-header sticky top-0 z-40 border-b border-brand-card bg-brand-nav/90 backdrop-blur-sm transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <a href="{{ route('home') }}" class="text-lg font-bold tracking-wide text-brand-text">
        <span class="inline-block w-3 h-3 rounded-full bg-brand-accent mr-2"></span>ArtCore
      </a>

      <div class="flex items-center gap-3">
        @guest
          <a class="btn btn-ghost" href="{{ route('login') }}">Login</a>
          <a class="btn btn-primary" href="{{ route('register') }}">Daftar</a>
        @endguest

        @auth
          @php($adminBase = Route::has('adminManage.dashboard') ? route('adminManage.dashboard') : url('/admin-manage'))
          @if (request()->is('admin-manage*'))
              {{-- SEDANG DI HALAMAN ADMIN: tampilkan menu CRUD admin --}}
              <a class="text-sm hover:underline" href="{{ $adminBase }}#dashboard-umum">Tinjauan</a>
              <a class="text-sm hover:underline" href="{{ $adminBase }}#manajemen-katalog">Katalog &amp; Unit</a>
              <a class="text-sm hover:underline" href="{{ $adminBase }}#manajemen-anggota">Anggota</a>
              <a class="text-sm hover:underline" href="{{ $adminBase }}#status-unit">Daftar Unit</a>
              <a class="text-sm hover:underline" href="{{ $adminBase }}#konfirmasi-pengembalian">Pengembalian</a>
              <a class="text-sm hover:underline" href="{{ $adminBase }}#riwayat-sewa">Riwayat Sewa</a>
          @else
              {{-- BUKAN halaman admin --}}
              @if($user->is_admin)
                <a class="text-sm hover:underline" href="{{ $adminBase }}">AdminManage</a>
              @else
                {{-- USER biasa --}}
                <a class="text-sm hover:underline" href="{{ route('cart') }}">Keranjang</a>
                <a class="text-sm hover:underline" href="{{ route('purchases') }}">Pembelian</a>
                <a class="text-sm hover:underline" href="{{ route('rentals.index') }}">Unit Disewa</a>
              @endif
          @endif

          <a class="text-sm hover:underline" href="{{ route('profile.edit') }}">Profil</a>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button class="text-sm hover:underline">Logout</button>
          </form>
        @endauth
      </div>
    </div>
  </header>

  @if(session('status'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
      <div class="card px-4 py-2">{{ session('status') }}</div>
    </div>
  @endif

  <main class="flex-1 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      @yield('content')
    </div>
  </main>

  <footer class="mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="card bg-brand-card p-6 flex items-center justify-between">
        <p class="text-sm text-brand-text/80">&copy; {{ date('Y') }} ArtCore</p>
        <div class="flex gap-2">
          <span class="w-3 h-3 rounded-full bg-brand-accent"></span>
          <span class="w-3 h-3 rounded-full bg-brand-card"></span>
        </div>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const header = document.querySelector('[data-main-header]');
      if (!header) return;
      const handleScroll = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 10);
      };
      handleScroll();
      window.addEventListener('scroll', handleScroll, { passive: true });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('form[data-scroll-anchor]').forEach(form => {
        form.addEventListener('submit', () => {
          const raw = form.getAttribute('data-scroll-anchor');
          if (!raw || form.dataset.anchorAppended === 'true') return;
          const anchor = raw.startsWith('#') ? raw : `#${raw}`;
          form.action = `${form.action.split('#')[0]}${anchor}`;
          form.dataset.anchorAppended = 'true';
        }, { once: true });
      });
    });
  </script>

  <div id="toast-container" class="fixed bottom-4 right-4 space-y-3 z-50 pointer-events-none"></div>

  <script>
    const showToastImpl = function(message, opts = {}) {
      if (!message) return;
      const container = document.getElementById('toast-container');
      if (!container) return;
      const toast = document.createElement('div');
      toast.className = 'toast-notice';
      toast.textContent = message;
      container.appendChild(toast);
      requestAnimationFrame(() => toast.classList.add('is-visible'));
      const duration = opts.duration ?? 5000;
      setTimeout(() => {
        toast.classList.remove('is-visible');
        setTimeout(() => toast.remove(), 220);
      }, duration);
    };
    window.showToast = showToastImpl;
    if (Array.isArray(window.__toastQueue)) {
      window.__toastQueue.forEach(item => showToastImpl(item.message, item.opts || {}));
      window.__toastQueue = [];
    }

    @if(session('toast'))
      document.addEventListener('DOMContentLoaded', () => {
        window.showToast(@json(session('toast')));
      });
    @endif
  </script>
  @auth
  @unless(auth()->user()->is_admin)
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const cartUrl = @json(route('cart'));
      const loginUrl = @json(route('login'));

      const bindAddToCart = (link) => {
        if (!link || link.dataset.cartBound) return;
        link.dataset.cartBound = '1';
        link.addEventListener('click', event => {
          const unitId = link.dataset.unitId;
          if (!unitId || !csrfToken) {
            return;
          }
          event.preventDefault();
          const redirectAfterAdd = sessionStorage.getItem('artcore.cart.redirect') === 'ready';

          fetch(@json(route('cart.add')), {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
            },
            body: JSON.stringify({ unit_id: unitId })
          })
          .then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
              if (response.status === 401) {
                window.location.assign(loginUrl);
                return;
              }
              if (data.redirect) {
                window.location.assign(link.dataset.cartUrl || cartUrl);
                return;
              }
              window.showToast(data.message || 'Gagal menambahkan unit ke keranjang.', { duration: 6000 });
              return;
            }
            sessionStorage.setItem('artcore.cart.redirect', 'ready');
            window.showToast(data.message || 'Unit masuk ke keranjang.');
            if (redirectAfterAdd) {
              window.location.assign(link.dataset.cartUrl || cartUrl);
            }
          })
          .catch(() => {
            window.location.assign(link.dataset.cartUrl || cartUrl);
          });
        });
      };

      document.querySelectorAll('.js-add-to-cart').forEach(bindAddToCart);

      document.addEventListener('artcore:refresh-add-to-cart', () => {
        document.querySelectorAll('.js-add-to-cart').forEach(bindAddToCart);
      });
    });
  </script>
  @endunless
  @endauth
</body>
</html>
