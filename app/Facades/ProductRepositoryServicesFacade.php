<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProductRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProductRepositoryServices';
    }
}
