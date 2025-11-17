<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckFreeAccountRestriction
{
    /**
     * Handle an incoming request.
     * Mencegah free account melakukan create/edit/delete
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Jika user adalah free account
        if ($user->plan === 'free' && !$user->is_owner) {
            // Cek method yang dilarang
            $method = $request->method();
            
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Kecuali untuk logout
                if ($request->routeIs('logout')) {
                    return $next($request);
                }
                
                return redirect()->back()
                    ->with('error', 'Akun Free hanya untuk demo. Fitur ini tidak tersedia untuk akun Free. Silakan upgrade ke plan Starter/Professional/Enterprise untuk menggunakan fitur lengkap.');
            }
        }

        return $next($request);
    }
}

