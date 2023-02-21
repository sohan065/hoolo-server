<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    public function createModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:50|unique:modules,name',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name']);
        try {
            $result = Module::create([
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

    public function editModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:modules,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        try {
            return Module::where('uuid', $validated['uuid'])->first();
        } catch (Exception $e) {
            Log::error($e);
        }
    }
    public function updateModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:modules,uuid',
            'name' => 'bail|required|string|min:3|max:50',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid', 'name']);
        $exist = Module::where('name', $validated['name'])->first();
        if ($exist) {
            return response(['message' => 'already exist'], 302);
        }
        try {
            $result = Module::where('uuid', $validated['uuid'])->update([
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
    public function deleteModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:modules,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        try {
            $result = Module::where('uuid', $validated['uuid'])->delete();
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
