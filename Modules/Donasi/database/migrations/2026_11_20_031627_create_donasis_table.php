<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_donasi')->unique();
            $table->string('nama_pangan');
            $table->string('lokasi_ambil');
            $table->unsignedInteger('batas_porsi');
            $table->date('tanggal_bagi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasis');
    }
};