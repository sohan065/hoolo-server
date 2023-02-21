<?php

namespace App\Http\Middleware;

use Token;
use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\RegisteredApp;

class AppMiddleware
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
        $app_key = $request->header('appkey');
        if (!$app_key) {
            return response('unauthorized', 401);
        }

        $exists = RegisteredApp::where('app_key', $app_key)->first();
        if (!$exists) {
            return response('unauthorized', 401);
        }

        $array = Token::decode($app_key);
        if (!$array) {
            return response('unauthorized', 401);
        }

        $uuid = $array['uuid'];
        $exists = RegisteredApp::where('uuid', $uuid)->first();
        if (!$exists) {
            return response('unauthorized', 401);
        }

        $expDate = strtotime($array['exp_date']);
        $curDate = strtotime(Carbon::now());
        if ($curDate > $expDate) {
            return response('renew app key', 402);
        }
        return $next($request);
    }
}
