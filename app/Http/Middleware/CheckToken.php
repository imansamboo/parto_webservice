<?php

namespace App\Http\Middleware;

use App\Http\Resources\User;
use Closure;


class CheckToken
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
        $user = \App\User::where('token', '=', $request->token)->firstOrFail();
        $user->lastactivity = time();
        $user->is_logged_out = 0;
        $user->save();
        return $next($request);
    }
}
