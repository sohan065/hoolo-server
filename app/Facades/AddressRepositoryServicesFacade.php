<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AddressRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AddressRepositoryServices';
    }
}
