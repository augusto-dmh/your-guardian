<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;
use Str;
use Symfony\Component\HttpFoundation\Response;

class StorePreviousUrlNotEdit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $previousUrl = url()->previous();
        $isEditUrl = preg_match('/\/\w+\/\d+\/edit/', $previousUrl);

        if (!$isEditUrl) {
            session(['previous_url_not_edit' => $previousUrl]);
        }

        return $next($request);
    }
}
