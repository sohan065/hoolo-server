<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BannerRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BannerRepositoryServices';
    }
}
