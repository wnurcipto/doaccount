<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_bukti',
        'tanggal_keluar',
        'barang_id',
        'periode_id',
        'customer',
        'qty',
        'harga',
        'subtotal',
        'metode_terima',
        'keterangan',
        'user_id',
        'jurnal_header_id'
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::created(function ($stokKeluar) {
            $stokKeluar->barang->updateStok();
        });

        static::deleted(function ($stokKeluar) {
            $stokKeluar->barang->updateStok();
        });

        static::updated(function ($stokKeluar) {
            $stokKeluar->barang->updateStok();
        });
    }

    // Relationships
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jurnalHeader()
    {
        return $this->belongsTo(JurnalHeader::class);
    }
}
