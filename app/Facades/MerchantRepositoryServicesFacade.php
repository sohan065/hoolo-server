<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MerchantRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MerchantRepositoryServices';
    }
}
