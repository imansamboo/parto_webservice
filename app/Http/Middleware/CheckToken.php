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
        try{
            $user = \App\User::where('token', '=', $request->token)->firstOrFail();
            $user->lastactivity = time();
            $user->is_logged_out = 0;
            $user->save();
            \Auth::login($user, true);
            return $next($request);
        }catch (\Exception $exception){
            return response()->json(['message' => 'you should try log in option', 'success' => false], 200);
        }
    }
}
