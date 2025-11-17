<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferingItem extends Model
{
    protected $fillable = [
        'offering_id',
        'nama_item',
        'deskripsi',
        'qty',
        'satuan',
        'harga',
        'total',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function offering()
    {
        return $this->belongsTo(Offering::class);
    }
}

