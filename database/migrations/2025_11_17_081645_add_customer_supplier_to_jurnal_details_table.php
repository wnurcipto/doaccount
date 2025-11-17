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
        Schema::table('jurnal_details', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('coa_id')->constrained('customers')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->after('customer_id')->constrained('suppliers')->onDelete('set null');
            
            // Index untuk performa
            $table->index('customer_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_details', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['customer_id', 'supplier_id']);
        });
    }
};
