@extends('layouts.app')
@section('title','Keranjang')
@section('content')
  <div class="card p-10 text-center">
    <div class="text-3xl mb-3">[keranjang]</div>
    <h2 class="text-xl font-semibold mb-2">Keranjang Masih Kosong</h2>
    <p class="text-sm text-brand-text/70">Pilih karya seni favoritmu untuk mulai menyewa.</p>
    <a href="{{ route('home') }}#katalog" class="btn btn-primary mt-5">Lihat Katalog</a>
  </div>
  <script>
    sessionStorage.removeItem('artcore.cart.redirect');
  </script>
@endsection

