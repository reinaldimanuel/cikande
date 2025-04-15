<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
   protected $routeMiddleware = [
    // Middleware lainnya
    'verify.token' => \App\Http\Middleware\EnsureTokenIsValid::class,
];
}