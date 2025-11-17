<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Get current authenticated user
     */
    protected function currentUser()
    {
        return Auth::user();
    }

    /**
     * Scope query untuk filter berdasarkan user_id (kecuali owner)
     */
    protected function scopeUser($query, $userId = null)
    {
        $user = $userId ?? $this->currentUser();
        
        if ($user && !$user->is_owner) {
            return $query->where('user_id', $user->id);
        }
        
        return $query;
    }

    /**
     * Check jika user adalah free account (hanya demo, tidak bisa create/edit/delete)
     */
    protected function isFreeAccount()
    {
        $user = $this->currentUser();
        return $user && $user->plan === 'free' && !$user->is_owner;
    }

    /**
     * Redirect free account dengan error message
     */
    protected function redirectFreeAccount()
    {
        return redirect()->back()
            ->with('error', 'Akun Free hanya untuk demo. Fitur ini tidak tersedia untuk akun Free. Silakan upgrade ke plan Starter/Professional/Enterprise untuk menggunakan fitur lengkap.');
    }

    /**
     * Filter query untuk free account (hanya tahun 2024)
     */
    protected function scopeFreeAccount($query, $tahunField = 'tahun')
    {
        $user = $this->currentUser();
        
        if ($this->isFreeAccount()) {
            return $query->where($tahunField, 2024);
        }
        
        return $query;
    }
}
