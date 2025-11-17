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
        Schema::create('jurnal_headers', function (Blueprint $table) {
            $table->id();
            $table->string('no_bukti', 50)->unique();
            $table->date('tanggal_transaksi');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('restrict');
            $table->text('deskripsi');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_kredit', 15, 2)->default(0);
            $table->enum('status', ['Draft', 'Posted', 'Void'])->default('Draft');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_headers');
    }
};
