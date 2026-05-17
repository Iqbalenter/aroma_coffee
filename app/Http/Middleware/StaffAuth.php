<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAuth
{
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (! session()->has('staff')) {
            return redirect()->route('login');
        }

        if ($role === 'admin' && session('staff.type') !== 'admin') {
            abort(403, 'Akses hanya untuk Admin.');
        }

        return $next($request);
    }
}
