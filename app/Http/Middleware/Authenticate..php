<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        // For API requests, return null to avoid redirection
        if ($request->expectsJson()) {
            return null;
        }

        // For web requests, redirect to the login route
        return route('login');
    }
}
