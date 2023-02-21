<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class InstructorRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'InstructorRepositoryServices';
    }
}
