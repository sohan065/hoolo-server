<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name']);
        try {
            $result = Role::create([
                'uuid' => Str::uuid(),
                'name' => $validated['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 201);
        }
        return response(['message' => 'fail'], 406);
    }

    public function editRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:roles,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        try {
            return Role::where('uuid', $validated['uuid'])->first();
        } catch (Exception $e) {
            Log::error($e);
        }
    }
    public function updateRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:roles,uuid',
            'name' => 'bail|required|string|min:3|max:50',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid', 'name']);
        $exist = Role::where('name', $validated['name'])->first();
        if ($exist) {
            return response(['message' => 'already exist'], 302);
        }
        try {
            $result = Role::where('uuid', $validated['uuid'])->update([
                'name' => $validated['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 201);
        }
        return response(['message' => 'fail'], 406);
    }
    public function deleteRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:roles,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        try {
            $result = Role::where('uuid', $validated['uuid'])->delete();
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 201);
        }
        return response(['message' => 'fail'], 406);
    }
}
