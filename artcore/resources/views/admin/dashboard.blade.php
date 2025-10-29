@extends('layouts.app')
@section('title','AdminManage')
@section('content')
  <section id="dashboard-umum" class="space-y-6 mb-12">
    <div class="space-y-2">
      <h1 class="text-2xl font-bold text-brand-text">AdminManage</h1>
      <p class="text-sm text-brand-text/70">Kelola seluruh operasi katalog, anggota, dan transaksi dari satu halaman terpadu.</p>
    </div>
    <div>
      <h2 class="font-semibold text-lg text-brand-text">Dashboard Umum</h2>
      <p class="text-sm text-brand-text/70">Ikhtisar cepat kondisi operasional saat ini.</p>
    </div>
    <div class="grid md:grid-cols-5 gap-4">
      <div class="card p-4"><div class="text-xs text-brand-text/60">Total Unit</div><div class="text-xl font-semibold">{{ $stats['units_total'] }}</div></div>
      <div class="card p-4"><div class="text-xs text-brand-text/60">Unit Tersedia</div><div class="text-xl font-semibold">{{ $stats['units_available'] }}</div></div>
      <div class="card p-4"><div class="text-xs text-brand-text/60">Sewa Aktif</div><div class="text-xl font-semibold">{{ $stats['rentals_active'] }}</div></div>
      <div class="card p-4"><div class="text-xs text-brand-text/60">Deposit Terkumpul</div><div class="text-xl font-semibold">Rp {{ number_format($stats['deposits_held'],0,',','.') }}</div></div>
      <div class="card p-4"><div class="text-xs text-brand-text/60">Pengembalian Menunggu</div><div class="text-xl font-semibold">{{ $stats['return_requests'] }}</div></div>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
      <div class="card p-5">
        <h2 class="font-semibold mb-3">Ringkasan Pengembalian</h2>
        <p class="text-sm text-brand-text/70">Ada {{ $stats['return_requests'] }} unit yang menunggu konfirmasi pengembalian.</p>
        <a href="{{ route('adminManage.dashboard') }}#konfirmasi-pengembalian" class="btn btn-primary mt-4 w-full">Pantau Pengembalian</a>
      </div>
      <div class="card p-5">
        <h2 class="font-semibold mb-3">Total Pengguna</h2>
        <p class="text-3xl font-semibold text-brand-text">{{ $stats['users_total'] }}</p>
        <p class="text-sm text-brand-text/70">Kelola anggota melalui sesi Manajemen Anggota.</p>
      </div>
    </div>
  </section>

  <section id="manajemen-katalog" class="space-y-4 mb-12">
    <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="font-semibold text-lg text-brand-text">Manajemen Katalog &amp; Unit</h2>
        <p class="text-sm text-brand-text/70">Atur kategori, tambahkan karya baru, dan pantau stok unit.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('adminManage.units.create') }}" class="btn btn-primary">Tambah Unit</a>
        <a href="{{ route('adminManage.units.index') }}" class="btn btn-ghost">Kelola Semua Unit</a>
        <a href="{{ route('adminManage.categories.index') }}" class="btn btn-ghost">Kelola Kategori</a>
      </div>
    </header>
    <div class="card overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-brand-nav/40">
          <tr>
            <th class="p-2 text-left">Nama</th>
            <th class="p-2 text-left">Kategori</th>
            <th class="p-2 text-center">Vintage</th>
            <th class="p-2 text-center">Harga Sewa</th>
            <th class="p-2 text-center">Status</th>
            <th class="p-2 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($latestUnits as $unit)
            @php
              $statusLabel = $unit->is_sold ? 'SOLD' : ($unit->is_available ? 'Tersedia' : 'Tidak Tersedia');
            @endphp
            <tr class="border-t">
              <td class="p-2 font-semibold text-brand-text">{{ $unit->name }}</td>
              <td class="p-2">{{ $unit->category->name ?? '-' }}</td>
              <td class="p-2 text-center">{{ $unit->vintage }}</td>
              <td class="p-2 text-center">Rp {{ number_format($unit->rent_price_5d,0,',','.') }}</td>
              <td class="p-2 text-center">{{ $statusLabel }}</td>
              <td class="p-2">
                <div class="flex items-center justify-center gap-2 text-xs">
                  <a href="{{ route('adminManage.units.edit', $unit) }}" class="underline">Edit</a>
                  <form method="POST" action="{{ route('adminManage.units.destroy', $unit) }}" onsubmit="return confirm('Hapus unit ini?')">
                    @csrf @method('DELETE')
                    <button class="text-rose-400 hover:text-rose-300">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="5">Belum ada unit baru.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card p-4 mt-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-brand-text">Daftar Kategori</h3>
        <a href="{{ route('adminManage.categories.create') }}" class="btn btn-primary text-xs px-3 py-1">Tambah</a>
      </div>
      <table class="w-full text-sm">
        <thead class="bg-brand-nav/30">
          <tr>
            <th class="p-2 text-left">Kategori</th>
            <th class="p-2 text-center">Jumlah Unit</th>
            <th class="p-2 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr class="border-t">
              <td class="p-2">{{ $category->name }}</td>
              <td class="p-2 text-center">{{ $category->units_count }}</td>
              <td class="p-2">
                <div class="flex items-center justify-center gap-2 text-xs">
                  <a href="{{ route('adminManage.categories.edit', $category) }}" class="underline">Edit</a>
                  <form method="POST" action="{{ route('adminManage.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button class="text-rose-400 hover:text-rose-300">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="3">Belum ada kategori.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section id="manajemen-anggota" class="space-y-4 mb-12">
    <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="font-semibold text-lg text-brand-text">Manajemen Anggota</h2>
        <p class="text-sm text-brand-text/70">Pantau dan kelola anggota aktif.</p>
      </div>
      <a href="{{ route('adminManage.users.create') }}" class="btn btn-primary">Tambah Anggota</a>
    </header>
    <div class="card overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-brand-nav/40">
          <tr>
            <th class="p-2 text-left">Nama</th>
            <th class="p-2 text-left">Email</th>
            <th class="p-2 text-center">Admin</th>
            <th class="p-2 text-center">Bergabung</th>
            <th class="p-2 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentUsers as $user)
            <tr class="border-t">
              <td class="p-2">{{ $user->name }}</td>
              <td class="p-2">{{ $user->email }}</td>
              <td class="p-2 text-center">{{ $user->is_admin ? 'Ya' : 'Tidak' }}</td>
              <td class="p-2 text-center">{{ $user->created_at?->format('d M Y') }}</td>
              <td class="p-2">
                <div class="flex items-center justify-center gap-2 text-xs">
                  <a href="{{ route('adminManage.users.edit', $user) }}" class="underline">Edit</a>
                  <form method="POST" action="{{ route('adminManage.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                    @csrf @method('DELETE')
                    <button class="text-rose-400 hover:text-rose-300">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="5">Belum ada anggota baru.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section id="status-unit" class="space-y-6 mb-12">
    <div class="flex flex-col gap-2">
      <h2 class="font-semibold text-lg text-brand-text">Daftar Unit</h2>
      <p class="text-sm text-brand-text/70">Monitor ketersediaan, unit aktif disewa, dan unit yang telah dibeli.</p>
    </div>
    <div class="grid lg:grid-cols-3 gap-4">
      <div class="card p-4">
        <h3 class="font-semibold mb-3">Tersedia</h3>
        <ul class="space-y-2 text-sm">
          @forelse($availableUnits as $unit)
            <li>
              <div class="font-semibold text-brand-text">{{ $unit->name }}</div>
              <div class="text-xs text-brand-text/60">{{ $unit->category->name ?? '-' }} &middot; {{ $unit->vintage }}</div>
            </li>
          @empty
            <li class="text-brand-text/60 text-sm">Tidak ada unit tersedia.</li>
          @endforelse
        </ul>
      </div>
      <div class="card p-4">
        <h3 class="font-semibold mb-3">Sedang Disewa</h3>
        <ul class="space-y-2 text-sm">
          @forelse($activeRentals as $rental)
            <li>
              <div class="font-semibold text-brand-text">{{ $rental->unit->name ?? '-' }}</div>
              <div class="text-xs text-brand-text/60">Oleh {{ $rental->user->name ?? '-' }} &middot; Mulai {{ $rental->rental_start?->format('d M Y') }}</div>
            </li>
          @empty
            <li class="text-brand-text/60 text-sm">Belum ada sewa aktif.</li>
          @endforelse
        </ul>
      </div>
      <div class="card p-4">
        <h3 class="font-semibold mb-3">Sudah Dibeli</h3>
        <ul class="space-y-2 text-sm">
          @forelse($purchasedRentals as $rental)
            <li>
              <div class="font-semibold text-brand-text">{{ $rental->unit->name ?? '-' }}</div>
              <div class="text-xs text-brand-text/60">Oleh {{ $rental->user->name ?? '-' }} &middot; {{ $rental->rental_end_actual?->format('d M Y') ?? '-' }}</div>
            </li>
          @empty
            <li class="text-brand-text/60 text-sm">Belum ada unit yang dibeli.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </section>

  <section id="konfirmasi-pengembalian" class="space-y-4 mb-12">
    <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="font-semibold text-lg text-brand-text">Konfirmasi Pengembalian Unit</h2>
        <p class="text-sm text-brand-text/70">Proses permintaan pengembalian dari pengguna.</p>
      </div>
      <a href="{{ route('adminManage.returns.index') }}" class="btn btn-ghost">Lihat Semua</a>
    </header>
    <div class="card overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-brand-nav/40">
          <tr>
            <th class="p-2 text-left">Unit</th>
            <th class="p-2 text-left">Pengguna</th>
            <th class="p-2 text-center">Diajukan</th>
            <th class="p-2 text-center">Jatuh Tempo</th>
            <th class="p-2 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($returnRequests as $rental)
            <tr class="border-t">
              <td class="p-2">{{ $rental->unit->name ?? '-' }}</td>
              <td class="p-2">{{ $rental->user->name ?? '-' }}</td>
              <td class="p-2 text-center">{{ $rental->return_requested_at?->format('d M Y H:i') ?? '-' }}</td>
              <td class="p-2 text-center">{{ $rental->rental_end_plan?->format('d M Y') ?? '-' }}</td>
              <td class="p-2 text-center">
                <a href="{{ route('adminManage.returns.form', $rental) }}" class="underline">Konfirmasi</a>
              </td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="5">Tidak ada permintaan pengembalian.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section id="riwayat-sewa" class="space-y-4 mb-12">
    <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="font-semibold text-lg text-brand-text">Riwayat Sewa</h2>
        <p class="text-sm text-brand-text/70">Ringkasan transaksi terbaru. Gunakan laporan lengkap untuk pencetakan.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('adminManage.reports.rentals') }}" class="btn btn-ghost">Lihat Detail</a>
        <a href="{{ route('adminManage.reports.rentals.export') }}" class="btn btn-primary">Unduh CSV</a>
      </div>
    </header>
    <div class="card overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-brand-teal/20">
          <tr>
            <th class="text-left p-2">User</th>
            <th class="text-left p-2">Unit</th>
            <th class="text-left p-2">Status</th>
            <th class="text-left p-2">Mulai</th>
            <th class="text-left p-2">Selesai</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rentalHistory as $rental)
            <tr class="border-t">
              <td class="p-2">{{ $rental->user->name ?? '-' }}</td>
              <td class="p-2">{{ $rental->unit->name ?? '-' }}</td>
              <td class="p-2">{{ $rental->status }}</td>
              <td class="p-2">{{ $rental->rental_start?->format('d M Y') ?? '-' }}</td>
              <td class="p-2">{{ $rental->rental_end_actual?->format('d M Y') ?? '-' }}</td>
            </tr>
          @empty
            <tr><td class="p-3 text-center text-brand-text/60" colspan="5">Belum ada transaksi.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection

