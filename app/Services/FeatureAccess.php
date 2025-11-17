<?php

namespace App\Services;

use App\Models\User;

class FeatureAccess
{
    /**
     * Definisi fitur untuk setiap plan
     */
    private static array $features = [
        'free' => [
            // Core Accounting
            'coa' => true, // COA selalu tersedia (global)
            'jurnal' => true,
            'buku_besar' => true,
            'laporan_laba_rugi' => true,
            
            // Premium Features
            'laporan_neraca' => false,
            'laporan_arus_kas' => false,
            'inventory' => false,
            'invoice' => false,
            'offering' => false,
            'surat_jalan' => false,
            'export_pdf' => false,
            'export_excel' => false,
            'duplicate_jurnal' => false,
            'import_csv' => false,
            
            // Limits
            'max_periodes' => 3,
            'max_jurnals_per_month' => 50,
        ],
        'starter' => [
            // Core Accounting
            'coa' => true,
            'jurnal' => true,
            'buku_besar' => true,
            'laporan_laba_rugi' => true,
            
            // Premium Features
            'laporan_neraca' => false,
            'laporan_arus_kas' => false,
            'inventory' => false,
            'invoice' => false,
            'offering' => false,
            'surat_jalan' => false,
            'export_pdf' => false,
            'export_excel' => false,
            'duplicate_jurnal' => false,
            'import_csv' => false,
            
            // Limits
            'max_periodes' => 6,
            'max_jurnals_per_month' => 200,
        ],
        'professional' => [
            // Core Accounting
            'coa' => true,
            'jurnal' => true,
            'buku_besar' => true,
            'laporan_laba_rugi' => true,
            'laporan_neraca' => true,
            'laporan_arus_kas' => true,
            
            // Premium Features
            'inventory' => true,
            'invoice' => true,
            'offering' => true,
            'surat_jalan' => true,
            'export_pdf' => true,
            'export_excel' => true,
            'duplicate_jurnal' => true,
            'import_csv' => true,
            
            // Limits
            'max_periodes' => 12,
            'max_jurnals_per_month' => 500,
        ],
        'enterprise' => [
            // Semua fitur unlimited
            'coa' => true,
            'jurnal' => true,
            'buku_besar' => true,
            'laporan_laba_rugi' => true,
            'laporan_neraca' => true,
            'laporan_arus_kas' => true,
            'inventory' => true,
            'invoice' => true,
            'offering' => true,
            'surat_jalan' => true,
            'export_pdf' => true,
            'export_excel' => true,
            'duplicate_jurnal' => true,
            'import_csv' => true,
            
            // Limits (unlimited = -1)
            'max_periodes' => -1,
            'max_jurnals_per_month' => -1,
        ],
    ];

    /**
     * Cek apakah user memiliki akses ke fitur tertentu
     */
    public static function userHasAccess(User $user, string $feature): bool
    {
        // Owner selalu punya akses penuh
        if ($user->is_owner) {
            return true;
        }

        $plan = $user->plan ?? 'free';
        
        // Cek apakah plan valid
        if (!isset(self::$features[$plan])) {
            return false;
        }

        return self::$features[$plan][$feature] ?? false;
    }

    /**
     * Get limit untuk user berdasarkan plan
     */
    public static function getLimit(User $user, string $limitType): int
    {
        // Owner tidak ada limit
        if ($user->is_owner) {
            return -1; // unlimited
        }

        $plan = $user->plan ?? 'free';
        
        if (!isset(self::$features[$plan])) {
            return 0;
        }

        return self::$features[$plan][$limitType] ?? 0;
    }

    /**
     * Get required plan untuk fitur tertentu
     */
    public static function getRequiredPlan(string $feature): string
    {
        foreach (['enterprise', 'professional', 'starter', 'free'] as $plan) {
            if (isset(self::$features[$plan][$feature]) && self::$features[$plan][$feature]) {
                return $plan;
            }
        }
        return 'enterprise';
    }

    /**
     * Get semua fitur yang tersedia untuk plan tertentu
     */
    public static function getFeaturesForPlan(string $plan): array
    {
        return self::$features[$plan] ?? [];
    }
}

