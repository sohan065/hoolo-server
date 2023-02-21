<?php

namespace App\Services;


use Exception;
use Token;
use App\Models\City;
use App\Models\State;
use App\Models\Thana;
use App\Models\Country;
use App\Models\PostCode;
use Illuminate\Support\Str;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Log;
use App\Repositories\AddressRepositoryInterface;

class AddressRepositoryServices implements AddressRepositoryInterface
{
    public function getAllUserShippingAddress($token)
    {
        $tokenInfo = Token::decode($token);
        return ShippingAddress::where('user_uuid', $tokenInfo['uuid'])->with('country:uuid,name', 'state:uuid,name', 'city:uuid,name', 'thana:uuid,name', 'postCode:uuid,name')->get();
    }
    public function storeShippingAddress($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        try {
            $result = ShippingAddress::create([
                'uuid' => Str::uuid(),
                'name' => $credentials['name'],
                'phone' => $credentials['phone'],
                'user_uuid' => $tokenInfo['uuid'],
                'address' => $credentials['address'],
                'country_uuid' => $credentials['country_uuid'],
                'state_uuid' => $credentials['state_uuid'],
                'city_uuid' => $credentials['city_uuid'],
                'thana_uuid' => $credentials['thana_uuid'] ? $credentials['thana_uuid'] : null,
                'post_code_uuid' => $credentials['post_code_uuid'],
            ]);
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message'=>'success'], 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
     // delete shipping address by uuid and User_uuid
    public function deleteShippingAddress($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        try {
            $result = ShippingAddress::where('uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->delete();
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 410);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // get all country
    public function getAllCountry()
    {
        return Country::all();
    }
    public function storeCountry($credentials)
    {
        try {
            $name = $credentials['name'];
            $uuid = Str::uuid();
            $result = Country::create([
                'name' => $name,
                'uuid' => $uuid,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public  function editCountry($uuid)
    {
        return Country::where('uuid', $uuid)->first();
    }
    public function updateCountry($credentials)
    {
        $exist = Country::where('name', $credentials['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = Country::where('uuid', $credentials['uuid'])->update([
                'name' => $credentials['name']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deleteCountry($uuid)
    {
        // to do
    }
    public function storeState($credentials)
    {
        try {
            $uuid = Str::uuid();
            $result = State::create([
                'uuid' => $uuid,
                'country_uuid' => $credentials['country_uuid'],
                'name' => $credentials['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editState($uuid)
    {
        return State::where('uuid', $uuid)->first();
    }
    // get all state 
    public function getAllState()
    {
        return State::all();
    }
    // get  state list by country uuid
    public function getStateInfo($credentials)
    {
        return State::where('country_uuid', $credentials['uuid'])->get();
    }
    public function updateState($credentials)
    {
        $exist = State::where('name', $credentials['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = State::where('uuid', $credentials['uuid'])->update([
                'name' => $credentials['name']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deleteState($uuid)
    {
        // to do
    }
    // get all city
    public function getAllCity()
    {
        return City::all();
    }
    // get all city by state uuid
    public function getCityInfo($credentials)
    {
        return City::where('state_uuid', $credentials['uuid'])->get();
    }
    public function storeCity($credentials)
    {
        try {
            $uuid = Str::uuid();
            $result = City::create([
                'uuid' => $uuid,
                'state_uuid' => $credentials['state_uuid'],
                'name' => $credentials['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editCity($uuid)
    {
        return City::where('uuid', $uuid)->first();
    }
    public function updateCity($credentials)
    {
        $exist = City::where('name', $credentials['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = City::where('uuid', $credentials['uuid'])->update([
                'name' => $credentials['name']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public  function deleteCity($uuid)
    {
        // to do
    }
    // get all thana
    public function getAllThana()
    {
        return Thana::all();
    }
    // get all thana by city uuid
    public function getThanaInfo($credentials)
    {
        return Thana::where('city_uuid', $credentials['uuid'])->get();
    }
    public function storeThana($credentials)
    {
        try {
            $uuid = Str::uuid();
            $result = Thana::create([
                'uuid' => $uuid,
                'city_uuid' => $credentials['city_uuid'],
                'name' => $credentials['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editThana($uuid)
    {
        return Thana::where('uuid', $uuid)->first();
    }
    public function updateThana($credentials)
    {
        $exist = Thana::where('name', $credentials['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = Thana::where('uuid', $credentials['uuid'])->update([
                'name' => $credentials['name']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deleteThana($uuid)
    {
        // to do
    }
    // get all post code
    public function getAllPostcode()
    {
        return PostCode::all();
    }
    // get all post code list by city uuid
    public function getAllPostcodeInfo($credentials)
    {
        return PostCode::where('city_uuid', $credentials['uuid'])->get();
    }
    // get all post code list by thana uuid
    public function getAllPostcodeInfoByThana($credentials)
    {
        return PostCode::where('thana_uuid', $credentials['uuid'])->get();
    }
    public function storePostcode($credentials)
    {
        try {
            $uuid = Str::uuid();
            $result = PostCode::create([
                'uuid' => $uuid,
                'city_uuid' => $credentials['city_uuid'] ? $credentials['city_uuid'] : null,
                'thana_uuid' => $credentials['thana_uuid'] ? $credentials['thana_uuid'] : null,
                'name' => $credentials['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editPostcode($uuid)
    {
        return PostCode::where('uuid', $uuid)->first();
    }
    public function updatePostcode($credentials)
    {
        $exist = PostCode::where('name', $credentials['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = PostCode::where('uuid', $credentials['uuid'])->update([
                'name' => $credentials['name']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deletePostcode($uuid)
    {
        // to do
    }
}
