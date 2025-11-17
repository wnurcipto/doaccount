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
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });
        
        // Update unique constraint untuk include user_id
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->dropUnique(['no_surat_jalan']);
            $table->unique(['no_surat_jalan', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->dropUnique(['no_surat_jalan', 'user_id']);
            $table->unique(['no_surat_jalan']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
