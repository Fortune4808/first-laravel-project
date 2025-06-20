<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $providedKey = $request->header('X-API-KEY');
        $validKey = env('APP_KEY');

        if (!$providedKey || $providedKey !== $validKey) {
            return response()->json(['message' => 'Unauthorized. Invalid API Key'], 401);
        }

        return $next($request);
    }
}

