<?php

namespace App\Repositories;

interface AttributesRepositoryInterface
{
    public  function store($credentials);
    public  function edit($uuid);
    public  function update($credentials);
    public  function delete($uuid);
}
