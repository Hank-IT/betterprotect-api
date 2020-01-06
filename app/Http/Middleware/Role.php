<?php

namespace App\Http\Middleware;

use App\Services\Authorizer;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (! app(Authorizer::class, ['user' => $request->user()])->isRole($role)) {
            throw new AuthorizationException('Sie sind nicht für die angeforderte Aktion berechtigt.');
        }

        return $next($request);
    }
}
