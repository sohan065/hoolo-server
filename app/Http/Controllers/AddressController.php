<?php

namespace App\Http\Controllers;

use Address;
use Validator;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getAllUserShippingAddress(Request $request)
    {
        $token = $request->header('token');
        return Address::getAllUserShippingAddress($token);
    }
    // store shipping address
    public function storeShippingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:100',
            'phone' => 'bail|required|min:10',
            'country_uuid' => 'bail|required|exists:countries,uuid',
            'state_uuid' => 'bail|required|exists:states,uuid',
            'city_uuid' => 'bail|required|exists:cities,uuid',
            'thana_uuid' => 'bail|nullable|exists:thanas,uuid',
            'post_code_uuid' => 'bail|required|exists:post_codes,uuid',
            'address' => 'bail|required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['name', 'phone', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid', 'address']);
        return Address::storeShippingAddress($validated, $token);
    }
        // delete shipping address by uuid & user_uuid
    public function deleteShippingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:shipping_addresses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $token = $request->header('token');
        $validated = $request->only(['uuid']);
        return Address::deleteShippingAddress($validated, $token);
    }
    function getAllCountry()
    {
        return Address::getAllCountry();
    }
    function storeCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|unique:countries,name',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['name']);
        return Address::storeCountry($validated);
    }
    function editCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:countries,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);
        return Address::editCountry($validated);
    }
    function updateCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50',
            'uuid' => 'bail|required|exists:countries,uuid'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['name', 'uuid']);

        return Address::updateCountry($validated);
    }
    function deleteCountry(Request $request)
    {
        //to do
    }
    // get all state
    public function getAllState()
    {
        return Address::getAllState();
    }
    // get  state list by country uuid
    public function getStateInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:countries,uuid',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Address::getStateInfo($validated);
    }
    function storeState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:50|unique:states,name',
            'country_uuid' => 'bail|required|exists:countries,uuid'
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['name', 'country_uuid']);
        return Address::storeState($validated);
    }

    function editState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:states,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);
        return Address::editState($validated);
    }

    function updateState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50',
            'uuid' => 'bail|required|exists:states,uuid'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'uuid']);

        return Address::updateState($validated);
    }
    function deleteState(Request $request)
    {
        // to do
    }
    // get all city
    public function getAllCity()
    {
        return Address::getAllCity();
    }
    // get  city list by state uuid
    public function getCityInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:states,uuid',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Address::getCityInfo($validated);
    }
    function storeCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:50|unique:cities,name',
            'state_uuid' => 'bail|required|exists:states,uuid'
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['name', 'state_uuid']);
        return Address::storeCity($validated);
    }
    function editCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:cities,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);
        return Address::editCity($validated);
    }
    function updateCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50',
            'uuid' => 'bail|required|exists:cities,uuid'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'uuid']);

        return Address::updateCity($validated);
    }
    function deleteCity(Request $request)
    {
        // to do
    }
    // get all thana 
    public function getAllThana()
    {
        return Address::getAllThana();
    }
    // get all thana by city uuid
    public function getThanaInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:cities,uuid',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Address::getThanaInfo($validated);
    }
    function storeThana(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|unique:thanas,name',
            'city_uuid' => 'bail|required|exists:cities,uuid'
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['name', 'city_uuid']);
        return Address::storeThana($validated);
    }
    function editThana(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:thanas,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);
        return Address::editThana($validated);
    }
    function updateThana(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50',
            'uuid' => 'bail|required|exists:thanas,uuid'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'uuid']);

        return Address::updateThana($validated);
    }
    function deleteThana(Request $request)
    {
        // to do
    }
    // get all post code
    public function getAllPostcode()
    {
        return Address::getAllPostcode();
    }
    // get all post code list by city uuid
    public function getAllPostcodeInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:cities,uuid',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Address::getAllPostcodeInfo($validated);
    }
    // get all post code list by thana uuid
    public function getAllPostcodeInfoByThana(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|min:3|exists:thanas,uuid',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Address::getAllPostcodeInfoByThana($validated);
    }
    function storePostcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:16|unique:post_codes,name',
            'city_uuid' => 'bail|nullable|exists:cities,uuid',
            'thana_uuid' => 'bail|required|exists:thanas,uuid'
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['name', 'city_uuid', 'thana_uuid']);
        return Address::storePostcode($validated);
    }
    function editPostcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:post_codes,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);
        return Address::editPostcode($validated);
    }
    function updatePostcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50',
            'uuid' => 'bail|required|exists:post_codes,uuid'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'uuid']);

        return Address::updatePostcode($validated);
    }
    function deletePostcode(Request $request)
    {
    }
}
