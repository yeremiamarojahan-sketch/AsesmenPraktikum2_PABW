<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Modules\Donasi\Models\Donasi;
use Modules\Pengajuan\Models\Pengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $totalKatalog        = Donasi::count();
            $transaksiMenunggu   = Pengajuan::where('status', 'Menunggu')->count();
            $totalPorsiDisetujui = Pengajuan::where('status', 'Disetujui')->sum('jumlah_porsi');

            return view('dashboard.admin', compact(
                'totalKatalog',
                'transaksiMenunggu',
                'totalPorsiDisetujui'
            ));
        }

        $pengajuanSaya = Pengajuan::with('donasi')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $jumlahTransaksiSaya = $pengajuanSaya->count();

        return view('dashboard.pemohon', compact(
            'pengajuanSaya',
            'jumlahTransaksiSaya'
        ));
    }
}