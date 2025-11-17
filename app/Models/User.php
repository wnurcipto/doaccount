<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\FeatureAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan',
        'is_owner',
        'plan_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_owner' => 'boolean',
            'plan_expires_at' => 'date',
        ];
    }

    /**
     * Relationships
     */
    public function periodes()
    {
        return $this->hasMany(Periode::class);
    }

    public function jurnals()
    {
        return $this->hasMany(JurnalHeader::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function offerings()
    {
        return $this->hasMany(Offering::class);
    }

    public function suratJalans()
    {
        return $this->hasMany(SuratJalan::class);
    }

    public function companyInfo()
    {
        return $this->hasOne(CompanyInfo::class);
    }

    /**
     * Helper Methods
     */
    public function hasFeature(string $feature): bool
    {
        return FeatureAccess::userHasAccess($this, $feature);
    }

    public function getLimit(string $limitType): int
    {
        return FeatureAccess::getLimit($this, $limitType);
    }

    public function isPlanActive(): bool
    {
        if ($this->is_owner) {
            return true;
        }

        if (!$this->plan_expires_at) {
            return true; // Free plan tidak ada expiry
        }

        return $this->plan_expires_at->isFuture();
    }

    public function getPlanDisplayName(): string
    {
        if ($this->is_owner) {
            return 'Owner';
        }

        return ucfirst($this->plan ?? 'free');
    }
}
