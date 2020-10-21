<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ApiResponse;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($this->isFrontend($request)) {
                return route('login');
            }
            return $this->errorResponse(trans('response.unauthenticated'), 401);
        }
    }
}