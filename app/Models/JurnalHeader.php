<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalHeader extends Model
{
    protected $fillable = [
        'no_bukti',
        'tanggal_transaksi',
        'periode_id',
        'deskripsi',
        'total_debit',
        'total_kredit',
        'status',
        'user_id'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total_debit' => 'decimal:2',
        'total_kredit' => 'decimal:2',
    ];

    // Relasi ke periode
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke jurnal details
    public function details()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk jurnal yang sudah diposting
    public function scopePosted($query)
    {
        return $query->where('status', 'Posted');
    }

    // Scope untuk jurnal draft
    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    // Method untuk validasi balance
    public function isBalanced()
    {
        return $this->total_debit == $this->total_kredit;
    }

    // Method untuk hitung total dari details
    public function calculateTotals()
    {
        $this->total_debit = $this->details()->where('posisi', 'Debit')->sum('jumlah');
        $this->total_kredit = $this->details()->where('posisi', 'Kredit')->sum('jumlah');
        $this->save();
    }
}
