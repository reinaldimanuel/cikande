<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Tambahkan ini untuk menggunakan Session

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Menonaktifkan CSRF untuk route /insert
        if ($request->is('insert')) {
            // Menambahkan pengecualian CSRF
            Session::forget('_token'); // Pastikan menggunakan Session::forget dengan token yang benar
        }

        return $next($request);
    }
}