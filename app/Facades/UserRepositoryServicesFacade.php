<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UserRepositoryServices';
    }
}
