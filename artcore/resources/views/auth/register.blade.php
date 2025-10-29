<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-bold text-brand-text">Daftar ArtCore</h1>
            <p class="text-sm text-brand-text/70">Buat akun untuk mulai menyewa atau mengelola katalog.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-brand-text/80">Nama</label>
                <input id="name" class="brand-input mt-1" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-brand-text/80">Email</label>
                <input id="email" class="brand-input mt-1" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-brand-text/80">Password</label>
                <input id="password" class="brand-input mt-1" type="password" name="password" required autocomplete="new-password">
                @error('password') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-brand-text/80">Konfirmasi Password</label>
                <input id="password_confirmation" class="brand-input mt-1" type="password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-2">
                <a class="brand-link" href="{{ route('login') }}">Sudah punya akun?</a>
                <button class="btn btn-primary">Daftar</button>
            </div>
        </form>
    </div>
</x-guest-layout>
