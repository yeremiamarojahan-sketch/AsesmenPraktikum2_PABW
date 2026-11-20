<?php

namespace Modules\Pengajuan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Donasi\Models\Donasi;
use Modules\Pengajuan\Models\Pengajuan;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Pengajuan::with(['donasi', 'user'])->latest();

        if ($user->role === 'pemohon') {
            $query->where('user_id', $user->id);
        }

        $pengajuans = $query->get();

        if ($request->expectsJson()) {
            return response()->json(['data' => $pengajuans]);
        }

        $donasis = Donasi::orderBy('nama_pangan')->get();
        return view('pengajuan::index', compact('pengajuans', 'donasis'));
    }

    public function show(Request $request, Pengajuan $pengajuan)
    {
        $user = $request->user();

        if ($user->role === 'pemohon' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat transaksi milik akun lain.');
        }

        return response()->json([
            'data' => $pengajuan->load(['donasi', 'user'])
        ]);
    }

    public function store(Request $request)
    {
        $donasi = Donasi::findOrFail($request->donasi_id);

        $validated = $request->validate([
            'donasi_id'     => ['required', 'exists:donasis,id'],
            'jumlah_porsi'  => ['required', 'integer', 'min:1', 'max:' . $donasi->batas_porsi],
            'tanggal_ambil' => ['required', 'date', 'in:' . $donasi->tanggal_bagi],
            'keperluan'     => ['required', 'string'],
            'foto_bukti'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('bukti_pengajuan', 'public');
        }
        $pengajuan = Pengajuan::create([
            'user_id'       => $request->user()->id,
            'donasi_id'     => $validated['donasi_id'],
            'jumlah_porsi'  => $validated['jumlah_porsi'],
            'tanggal_ambil' => $validated['tanggal_ambil'],
            'keperluan'     => $validated['keperluan'],
            'status'        => 'Menunggu',
            'foto_bukti'    => $path,
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim.',
            'data'    => $pengajuan->load(['donasi', 'user']),
        ], 201);
    }

    public function edit(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizePemohonPending($request->user(), $pengajuan);

        return response()->json(['data' => $pengajuan]);
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizePemohonPending($request->user(), $pengajuan);

        $donasi = Donasi::findOrFail($request->donasi_id);

        $validated = $request->validate([
            'donasi_id'     => ['required', 'exists:donasis,id'],
            'jumlah_porsi'  => ['required', 'integer', 'min:1', 'max:' . $donasi->batas_porsi],
            'tanggal_ambil' => ['required', 'date', 'in:' . $donasi->tanggal_bagi],
            'keperluan'     => ['required', 'string'],
            'foto_bukti'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $path = $pengajuan->foto_bukti;
        if ($request->hasFile('foto_bukti')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('foto_bukti')->store('bukti_pengajuan', 'public');
        }
        $pengajuan->update([
            'donasi_id'     => $validated['donasi_id'],
            'jumlah_porsi'  => $validated['jumlah_porsi'],
            'tanggal_ambil' => $validated['tanggal_ambil'],
            'keperluan'     => $validated['keperluan'],
            'foto_bukti'    => $path,
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil diperbarui.',
            'data'    => $pengajuan->load(['donasi', 'user']),
        ]);
    }

    public function keputusan(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'Menunggu') {
            abort(403, 'Keputusan final sudah ditetapkan dan tidak dapat diubah kembali.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:Disetujui,Ditolak'],
        ]);

        $pengajuan->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Status pengajuan diperbarui menjadi ' . $validated['status'] . '.',
            'data'    => $pengajuan->load(['donasi', 'user']),
        ]);
    }

    public function destroy(Request $request, Pengajuan $pengajuan)
    {
        $user = $request->user();
        if ($user->role === 'pemohon') {
            $this->authorizePemohonPending($user, $pengajuan);
        }

        if ($pengajuan->foto_bukti) {
            Storage::disk('public')->delete($pengajuan->foto_bukti);
        }

        $pengajuan->delete();

        return response()->json([
            'message' => 'Transaksi pengajuan berhasil dihapus.',
        ]);
    }

    private function authorizePemohonPending($user, Pengajuan $pengajuan): void
    {
        if ($pengajuan->user_id !== $user->id) {
            abort(403, 'Akses ditolak: Anda tidak berhak mengakses transaksi milik akun lain.');
        }

        if ($pengajuan->status !== 'Menunggu') {
            abort(403, 'Akses ditolak: transaksi yang sudah diproses tidak dapat diubah atau dihapus.');
        }
    }
}