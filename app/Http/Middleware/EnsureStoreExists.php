<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only apply to users with 'admin' role (sellers)
        if ($user && $user->role === 'admin') {
            // Check if they have at least one store
            if ($user->stores()->count() === 0) {
                // If they are not already on the store registration page, redirect them
                if (!$request->routeIs('admin.register-store') && !$request->routeIs('admin.register-store.submit')) {
                    return redirect()->route('admin.register-store')
                        ->with('info', 'Silakan daftarkan toko Anda terlebih dahulu sebelum masuk ke dashboard.');
                }
            } else {
                // If they already have a store and try to access store registration, redirect to dashboard
                if ($request->routeIs('admin.register-store') || $request->routeIs('admin.register-store.submit')) {
                    return redirect()->route('admin.dashboard');
                }
            }
        }

        return $next($request);
    }
}
