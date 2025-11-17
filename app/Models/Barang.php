<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimal',
        'keterangan',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['stok_status'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class);
    }

    public function stokKeluars()
    {
        return $this->hasMany(StokKeluar::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStokRendah($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimal');
    }

    // Methods
    public function updateStok()
    {
        $totalMasuk = $this->stokMasuks()->sum('qty');
        $totalKeluar = $this->stokKeluars()->sum('qty');
        
        $this->stok = $totalMasuk - $totalKeluar;
        $this->save();
        
        return $this->stok;
    }

    public function isStokRendah()
    {
        return $this->stok <= $this->stok_minimal;
    }

    public function getStokStatusAttribute()
    {
        if ($this->stok <= 0) {
            return 'Habis';
        } elseif ($this->stok <= $this->stok_minimal) {
            return 'Rendah';
        } else {
            return 'Tersedia';
        }
    }
}
