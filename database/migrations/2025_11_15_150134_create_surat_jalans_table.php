<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan')->unique();
            $table->date('tanggal');
            $table->string('dari_nama');
            $table->text('dari_alamat')->nullable();
            $table->string('dari_kota')->nullable();
            $table->string('dari_telepon')->nullable();
            $table->string('kepada_nama');
            $table->text('kepada_alamat')->nullable();
            $table->string('kepada_kota')->nullable();
            $table->string('kepada_telepon')->nullable();
            $table->string('no_kendaraan')->nullable();
            $table->string('nama_supir')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
