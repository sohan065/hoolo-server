<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CourseRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CourseRepositoryServices';
    }
}
