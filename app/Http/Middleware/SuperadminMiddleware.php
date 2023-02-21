<?php

namespace App\Http\Middleware;

use Token;
use Closure;
use App\Models\Superadmin;
use App\Models\SuperadminAccessToken;
use Illuminate\Http\Request;

class SuperadminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('_token');
        if(!$token){
            return response('unauthorized',401);
        }

        $exists = SuperadminAccessToken::where('token',$token)->first();
        if(!$exists){
            return response('unauthorized',401);
        }

        $array = Token::decode($token);
        if(!$array){
            return response('unauthorized',401);
        }

        $uuid = $array['uuid'];
        $exists = Superadmin::where('uuid',$uuid)->first();
        if(!$exists){
            return response('unauthorized',401);
        }
        return $next($request);
    }
}
