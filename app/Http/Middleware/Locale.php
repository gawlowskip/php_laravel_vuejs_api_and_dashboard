<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array(session()->get('locale'), config()->get('app.locales'))) {
            $locale = session()->get('locale');
        } else {
            $locale = config()->get('app.locale');
        }
        app()->setLocale($locale);

        return $next($request);
    }
}