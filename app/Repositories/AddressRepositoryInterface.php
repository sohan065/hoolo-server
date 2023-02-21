<?php

namespace App\Repositories;


interface  AddressRepositoryInterface
{
    public  function storeCountry($credentials);
    public  function editCountry($uuid);
    public  function updateCountry($credentials);
    public  function deleteCountry($uuid);

    public  function storeState($credentials);
    public  function editState($uuid);
    public  function updateState($credentials);
    public  function deleteState($uuid);

    public  function storeCity($credentials);
    public  function editCity($uuid);
    public  function updateCity($credentials);
    public  function deleteCity($uuid);

    public  function storeThana($credentials);
    public  function editThana($uuid);
    public  function updateThana($credentials);
    public  function deleteThana($uuid);

    public  function storePostcode($credentials);
    public  function editPostcode($uuid);
    public  function updatePostcode($credentials);
    public  function deletePostcode($uuid);
}
