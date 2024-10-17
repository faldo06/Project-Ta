<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login'); // Jika belum login, arahkan ke login
        }

        $user = Auth::user();

        // Cek apakah user memiliki role yang diizinkan
        if ($user->role !== $role) {
            abort(403, 'Unauthorized'); // Jika tidak cocok, berikan error 403
        }

        return $next($request);
    }
}
