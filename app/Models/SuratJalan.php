<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $fillable = [
        'no_surat_jalan',
        'tanggal',
        'dari_nama',
        'dari_alamat',
        'dari_kota',
        'dari_telepon',
        'kepada_nama',
        'kepada_alamat',
        'kepada_kota',
        'kepada_telepon',
        'no_kendaraan',
        'nama_supir',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SuratJalanItem::class);
    }
}
