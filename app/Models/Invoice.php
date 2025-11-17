<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'no_invoice',
        'tanggal',
        'kepada_nama',
        'kepada_alamat',
        'kepada_kota',
        'kepada_telepon',
        'keterangan',
        'catatan',
        'term_condition',
        'payment_terms',
        'signature_name',
        'subtotal',
        'diskon',
        'ppn',
        'dp',
        'total',
        'status',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'ppn' => 'decimal:2',
        'dp' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
