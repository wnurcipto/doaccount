<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'user_id',
        'kode_supplier',
        'nama_supplier',
        'nama_kontak',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke jurnal details (hutang)
    public function jurnalDetails()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    // Scope untuk supplier aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter berdasarkan user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk menghitung saldo hutang
    public function getSaldoHutangAttribute()
    {
        // Ambil akun hutang (2-1001 - Utang Usaha)
        $hutangCoa = Coa::where('kode_akun', '2-1001')->first();
        
        if (!$hutangCoa) {
            return 0;
        }

        // Hitung total kredit (hutang bertambah) dikurangi debit (hutang berkurang)
        $totalKredit = JurnalDetail::where('supplier_id', $this->id)
            ->where('coa_id', $hutangCoa->id)
            ->where('posisi', 'Kredit')
            ->sum('jumlah');

        $totalDebit = JurnalDetail::where('supplier_id', $this->id)
            ->where('coa_id', $hutangCoa->id)
            ->where('posisi', 'Debit')
            ->sum('jumlah');

        return $totalKredit - $totalDebit;
    }

    // Method untuk mendapatkan transaksi hutang
    public function getTransaksiHutang($startDate = null, $endDate = null)
    {
        $hutangCoa = Coa::where('kode_akun', '2-1001')->first();
        
        if (!$hutangCoa) {
            return collect([]);
        }

        $query = JurnalDetail::where('supplier_id', $this->id)
            ->where('coa_id', $hutangCoa->id)
            ->with(['jurnalHeader', 'coa']);

        if ($startDate) {
            $query->whereHas('jurnalHeader', function($q) use ($startDate) {
                $q->where('tanggal_transaksi', '>=', $startDate);
            });
        }

        if ($endDate) {
            $query->whereHas('jurnalHeader', function($q) use ($endDate) {
                $q->where('tanggal_transaksi', '<=', $endDate);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
