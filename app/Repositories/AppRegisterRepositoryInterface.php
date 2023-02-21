<?php

namespace App\Repositories;


interface AppRegisterRepositoryInterface
{
    public  function registration($credentials);
    public  function inactive($uuid);
    public  function active($uuid);
    public  function delete($uuid);
}
