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
        Schema::create('offering_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offering_id')->constrained()->onDelete('cascade');
            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->integer('qty')->default(1);
            $table->string('satuan')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offering_items');
    }
};
