<?php

namespace App\Http\Controllers;

use App\Jobs\Mailnotify;
use Illuminate\Http\Request;
use Artisan;

class TestController extends Controller
{
    public function cacheClear()
    {
        $config = Artisan::call('config:cache');
        $route  = Artisan::call('route:cache');
        $view   = Artisan::call('view:cache');
        $queue = Artisan::call('queue:listen');
        if ($config == 0 && $route == 0 && $view == 0) {
            return response('success', 200);
        }
        return response('success', 404);
    }
}
