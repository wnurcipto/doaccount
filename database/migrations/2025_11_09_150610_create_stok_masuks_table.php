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
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_bukti', 30)->unique();
            $table->date('tanggal_masuk');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('restrict');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('restrict');
            $table->string('supplier', 100)->nullable();
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('jurnal_header_id')->nullable()->constrained('jurnal_headers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};
