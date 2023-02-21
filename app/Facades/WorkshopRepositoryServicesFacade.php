<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkshopRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'WorkshopRepositoryServices';
    }
}
