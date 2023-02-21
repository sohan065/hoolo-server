<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SuperAdminRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SuperAdminRepositoryServices';
    }
}
