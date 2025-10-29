<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-bold text-brand-text">Masuk ke ArtCore</h1>
            <p class="text-sm text-brand-text/70">Silakan masuk untuk mengelola katalog dan sewa favorit Anda.</p>
        </div>

        <x-auth-session-status class="mb-2 text-brand-text/70" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-brand-text/80">Email</label>
                <input id="email" class="brand-input mt-1" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-brand-text/80">Password</label>
                <input id="password" class="brand-input mt-1" type="password" name="password" required autocomplete="current-password">
                @error('password') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-2">
                @if (Route::has('password.request'))
                    <a class="brand-link" href="{{ route('password.request') }}">Lupa password?</a>
                @else
                    <span></span>
                @endif
                <button class="btn btn-primary">Masuk</button>
            </div>
        </form>

        <p class="text-center text-xs text-brand-text/60">
            Belum punya akun?
            <a href="{{ route('register') }}" class="brand-link">Daftar sekarang</a>
        </p>
    </div>
</x-guest-layout>
