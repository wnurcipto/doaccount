<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'kode_customer',
        'nama_customer',
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

    // Relasi ke jurnal details (piutang)
    public function jurnalDetails()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    // Scope untuk customer aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter berdasarkan user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk menghitung saldo piutang
    public function getSaldoPiutangAttribute()
    {
        // Ambil akun piutang (1-1003 - Piutang Usaha)
        $piutangCoa = Coa::where('kode_akun', '1-1003')->first();
        
        if (!$piutangCoa) {
            return 0;
        }

        // Hitung total debit (piutang bertambah) dikurangi kredit (piutang berkurang)
        $totalDebit = JurnalDetail::where('customer_id', $this->id)
            ->where('coa_id', $piutangCoa->id)
            ->where('posisi', 'Debit')
            ->sum('jumlah');

        $totalKredit = JurnalDetail::where('customer_id', $this->id)
            ->where('coa_id', $piutangCoa->id)
            ->where('posisi', 'Kredit')
            ->sum('jumlah');

        return $totalDebit - $totalKredit;
    }

    // Method untuk mendapatkan transaksi piutang
    public function getTransaksiPiutang($startDate = null, $endDate = null)
    {
        $piutangCoa = Coa::where('kode_akun', '1-1003')->first();
        
        if (!$piutangCoa) {
            return collect([]);
        }

        $query = JurnalDetail::where('customer_id', $this->id)
            ->where('coa_id', $piutangCoa->id)
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
