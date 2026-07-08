<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            return redirect()->route('home')->with('error', 'انتظامی حصے تک رسائی کے لیے منتظم ہونا ضروری ہے۔');
        }

        return $next($request);
    }
}
