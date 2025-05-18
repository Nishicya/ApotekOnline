<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleUser
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // If user is not authenticated, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // If no role matched, redirect to appropriate dashboard
        $dashboardRoutes = [
            'admin' => 'admin.dashboard',
            'apoteker' => 'apoteker.dashboard',
            'pemilik' => 'pemilik.dashboard',
            'karyawan' => 'karyawan.dashboard',
            'kasir' => 'kasir.dashboard',
            'kurir' => 'kurir.dashboard',
        ];

        $redirectRoute = $dashboardRoutes[$user->role] ?? 'home';
        
        return redirect()->route($redirectRoute)->with('error', 'Unauthorized access');
    }                               
}