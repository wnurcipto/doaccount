<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_bukti',
        'tanggal_masuk',
        'barang_id',
        'periode_id',
        'supplier',
        'qty',
        'harga',
        'subtotal',
        'metode_bayar',
        'keterangan',
        'user_id',
        'jurnal_header_id'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::created(function ($stokMasuk) {
            $stokMasuk->barang->updateStok();
        });

        static::deleted(function ($stokMasuk) {
            $stokMasuk->barang->updateStok();
        });

        static::updated(function ($stokMasuk) {
            $stokMasuk->barang->updateStok();
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
