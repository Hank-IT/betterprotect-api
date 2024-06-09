<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;

class QueueWebsocketAppKeyCookie
{
    public function __construct(
        protected CookieJar $cookieJar,
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->setCookie( $this->cookieJar->make(
            name: 'websocket-app-key',
            value: config('broadcasting.connections.soketi.key'),
            minutes: Carbon::parse('01.01.2038')->diffInMinutes(Carbon::now()),
            domain: parse_url(config('app.url'))['host'],
            secure: true,
            httpOnly: false,
        ));

        return $response;
    }
}
