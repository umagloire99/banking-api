<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $apiKey = $request->header('Api-Key');
        if (!$apiKey || config('app.api_key') != $apiKey) {
            return response([
                'message' => 'Access denied! Invalid Api Key. Make sure to provide a valid key'
            ], 403);
        }
        return $next($request);
    }
}
