<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CategoryRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CategoryRepositoryServices';
    }
}
