@extends('layouts.app')

@section('content')
<h3>Dashboard Pemohon</h3>
<div class="card p-3 my-3 shadow-sm">
    <h6 class="text-muted">Jumlah Pengajuan Saya</h6>
    <h2>{{ $jumlahTransaksiSaya }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode / Pangan</th>
                    <th>Jumlah Porsi</th>
                    <th>Tanggal Ambil</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanSaya as $item)
                <tr>
                    <td>{{ $item->donasi->kode_donasi ?? '-' }} - {{ $item->donasi->nama_pangan ?? '-' }}</td>
                    <td>{{ $item->jumlah_porsi }}</td>
                    <td>{{ $item->tanggal_ambil }}</td>
                    <td><span class="badge bg-secondary">{{ $item->status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada pengajuan bantuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection