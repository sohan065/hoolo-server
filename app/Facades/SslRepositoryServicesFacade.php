<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SslRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SslRepositoryServices';
    }
}
