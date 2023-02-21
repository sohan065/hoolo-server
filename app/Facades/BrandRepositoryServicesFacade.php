<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BrandRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BrandRepositoryServices';
    }
}
