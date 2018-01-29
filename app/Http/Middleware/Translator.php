<?php

namespace App\Http\Middleware;

use Closure;

class Translator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (empty($user->is_translator) || $user->is_translator == false) {
            return redirect()->route('home');
        }
        return $next($request);
    }
}