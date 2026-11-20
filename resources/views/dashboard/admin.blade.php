@extends('layouts.app')

@section('content')
<h3>Dashboard Admin</h3>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted">Total Katalog Donasi</h6>
            <h2>{{ $totalKatalog }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted">Transaksi Menunggu</h6>
            <h2>{{ $transaksiMenunggu }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted">Total Porsi Disetujui (SDG 2)</h6>
            <h2>{{ $totalPorsiDisetujui }} Porsi</h2>
        </div>
    </div>
</div>
@endsection