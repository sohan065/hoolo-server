<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FileSystemRepositoryServicesFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'FileSystemRepositoryServices';
    }
}
