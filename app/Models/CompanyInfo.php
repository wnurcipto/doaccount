<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'company_infos';

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'logo',
        'footer_text',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get single company info (singleton pattern) - per user
     */
    public static function getInfo($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        return static::where('user_id', $userId)->first() ?? static::create([
            'nama_perusahaan' => 'PT. Do Accounting Serve',
            'alamat' => 'Jl. Contoh No. 123',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12345',
            'telepon' => '(021) 12345678',
            'email' => 'admin@do-account.id',
            'website' => 'admin@do-account.id',
            'footer_text' => 'Terima kasih atas kepercayaan Anda',
            'user_id' => $userId,
        ]);
    }
}
