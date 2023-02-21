<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BkashRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BkashRepositoryServices';
    }
}
