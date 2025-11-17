<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'tipe_akun',
        'posisi_normal',
        'parent_id',
        'level',
        'is_active',
        'deskripsi'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    // Relasi ke jurnal details
    public function jurnalDetails()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    // Relasi parent (untuk hierarchical COA)
    public function parent()
    {
        return $this->belongsTo(Coa::class, 'parent_id', 'kode_akun');
    }

    // Relasi children
    public function children()
    {
        return $this->hasMany(Coa::class, 'parent_id', 'kode_akun');
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeTipeAkun($query, $tipe)
    {
        return $query->where('tipe_akun', $tipe);
    }

    // Scope untuk akun aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
