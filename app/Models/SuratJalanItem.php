<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalanItem extends Model
{
    protected $fillable = [
        'surat_jalan_id',
        'nama_item',
        'deskripsi',
        'qty',
        'satuan',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    public function suratJalan()
    {
        return $this->belongsTo(SuratJalan::class);
    }
}

