<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    protected $fillable = [
        'jurnal_header_id',
        'coa_id',
        'customer_id',
        'supplier_id',
        'posisi',
        'jumlah',
        'keterangan'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    // Relasi ke jurnal header
    public function jurnalHeader()
    {
        return $this->belongsTo(JurnalHeader::class);
    }

    // Relasi ke COA
    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scope untuk filter debit
    public function scopeDebit($query)
    {
        return $query->where('posisi', 'Debit');
    }

    // Scope untuk filter kredit
    public function scopeKredit($query)
    {
        return $query->where('posisi', 'Kredit');
    }
}
