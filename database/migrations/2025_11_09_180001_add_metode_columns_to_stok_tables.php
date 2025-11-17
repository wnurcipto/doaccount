<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('stok_masuks', 'metode_bayar')) {
            Schema::table('stok_masuks', function (Blueprint $table) {
                $table->enum('metode_bayar', ['tunai','kredit'])->default('tunai')->after('subtotal');
            });
        }
        if (!Schema::hasColumn('stok_keluars', 'metode_terima')) {
            Schema::table('stok_keluars', function (Blueprint $table) {
                $table->enum('metode_terima', ['tunai','kredit'])->default('tunai')->after('subtotal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('stok_masuks', 'metode_bayar')) {
            Schema::table('stok_masuks', function (Blueprint $table) {
                $table->dropColumn('metode_bayar');
            });
        }
        if (Schema::hasColumn('stok_keluars', 'metode_terima')) {
            Schema::table('stok_keluars', function (Blueprint $table) {
                $table->dropColumn('metode_terima');
            });
        }
    }
};