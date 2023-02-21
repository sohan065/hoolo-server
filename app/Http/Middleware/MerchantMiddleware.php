<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MerchantAccessToken;
use Token;

class MerchantMiddleware
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
        if (!$token) {
            return response('unauthorized', 401);
        }
        $exists = MerchantAccessToken::where('token', $token)->first();
        if (!$exists) {
            return response('unauthorized', 401);
        }
        $array = Token::decode($token);
        if (!$array) {
            return response('unauthorized', 401);
        }
        $expDate = strtotime($array['exp_date']);
        $curDate = strtotime(Carbon::now());
        if ($curDate > $expDate) {
            return response('login again', 402);
        }
        return $next($request);
    }
}
