<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $fillable = [
        'tahun',
        'bulan',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'user_id',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke jurnal headers
    public function jurnalHeaders()
    {
        return $this->hasMany(JurnalHeader::class);
    }

    // Scope untuk periode yang open
    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    // Scope untuk periode yang closed
    public function scopeClosed($query)
    {
        return $query->where('status', 'Closed');
    }

    // Method untuk mendapatkan nama periode
    public function getNamaPeriodeAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanNama[$this->bulan] . ' ' . $this->tahun;
    }
}
