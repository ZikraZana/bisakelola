<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini di-import
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  array<string>  ...$roles (BERUBAH: Menerima BANYAK role)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ambil user dari guard DEFAULT (sesuai 'auth' di web.php)
        $user = Auth::user();

        // 2. Cek jika user login DAN rolenya ada di dalam array $roles
        if (!$user || !in_array($user->role, $roles)) {
            // Jika tidak, tolak akses
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK TINDAKAN INI.');
        }

        // 3. Jika lolos, lanjutkan
        return $next($request);
    }
}
