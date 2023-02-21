<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Attributes;
use Validator;

class AttributesController extends Controller
{
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:16|unique:attributes,name',
            'requested' => 'bail|nullable|boolean',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'requested']);

        return Attributes::store($validated);
    }

    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:16',
            'uuid' => 'bail|required|string|exists:attributes,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'uuid']);

        return Attributes::update($validated);
    }

    function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:attributes,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);

        return Attributes::edit($validated);
    }
}
