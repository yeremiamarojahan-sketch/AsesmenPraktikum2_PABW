<?php

namespace Modules\Pengajuan\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Donasi\Models\Donasi;

class Pengajuan extends Model
{
    protected $fillable = [
        'user_id',
        'donasi_id',
        'jumlah_porsi',
        'tanggal_ambil',
        'keperluan',
        'status',
        'foto_bukti',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donasi()
    {
        return $this->belongsTo(Donasi::class, 'donasi_id');
    }
}