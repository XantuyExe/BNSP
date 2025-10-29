@extends('layouts.app')
@section('title','Sewa Aktif')
@section('content')
  <h1 class="text-2xl font-semibold mb-4">Transaksi Sewa Aktif</h1>

  <div class="card p-4 mb-4">
    <form method="GET" class="grid sm:grid-cols-4 gap-3">
      <input type="text" name="user" value="{{ request('user') }}" placeholder="Cari user" class="border rounded-lg px-3 py-2">
      <input type="text" name="unit" value="{{ request('unit') }}" placeholder="Cari unit" class="border rounded-lg px-3 py-2">
      <select name="status" class="border rounded-lg px-3 py-2">
        <option value="">Semua Status</option>
        <option value="ACTIVE" @selected(request('status')==='ACTIVE')>ACTIVE</option>
        <option value="RETURN_REQUESTED" @selected(request('status')==='RETURN_REQUESTED')>RETURN REQUESTED</option>
      </select>
      <button class="btn btn-ghost md:col-start-4">Filter</button>
    </form>
  </div>

  <div class="card overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-brand-nav/40">
        <tr>
          <th class="p-2 text-left">User</th>
          <th class="p-2 text-left">Unit</th>
          <th class="p-2">Status</th>
          <th class="p-2">Mulai</th>
          <th class="p-2">Jatuh Tempo</th>
          <th class="p-2">Permintaan</th>
          <th class="p-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rentals as $r)
          <tr class="border-t">
            <td class="p-2">{{ $r->user->name }}</td>
            <td class="p-2">{{ $r->unit->name }}</td>
            <td class="p-2 text-center">{{ $r->status }}</td>
            <td class="p-2 text-center">{{ $r->rental_start?->format('d M Y') }}</td>
            <td class="p-2 text-center">{{ $r->rental_end_plan?->format('d M Y') }}</td>
            <td class="p-2 text-center">{{ $r->return_requested_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="p-2">
              <a href="{{ route('adminManage.rentals.show', $r) }}" class="underline">Detail</a>
              @if($r->status==='RETURN_REQUESTED')
                <a href="{{ route('adminManage.returns.form', $r) }}" class="underline ml-2">Konfirmasi</a>
              @endif
            </td>
          </tr>
        @empty
          <tr><td class="p-3 text-center text-brand-text/60" colspan="7">Tidak ada sewa aktif.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $rentals->links() }}</div>
@endsection


