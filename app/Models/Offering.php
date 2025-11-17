<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    protected $fillable = [
        'no_offering',
        'tanggal',
        'kepada_nama',
        'kepada_alamat',
        'kepada_kota',
        'kepada_telepon',
        'keterangan',
        'catatan',
        'subtotal',
        'diskon',
        'ppn',
        'total',
        'tanggal_berlaku',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_berlaku' => 'date',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'ppn' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OfferingItem::class);
    }
}
