<?php

namespace App\Http\Middleware;

use App\Services\FeatureAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!FeatureAccess::userHasAccess($user, $feature)) {
            $requiredPlan = FeatureAccess::getRequiredPlan($feature);
            
            return redirect()->route('dashboard')
                ->with('error', "Fitur ini hanya tersedia untuk plan " . ucfirst($requiredPlan) . " ke atas. Silakan upgrade plan Anda.");
        }

        return $next($request);
    }
}
