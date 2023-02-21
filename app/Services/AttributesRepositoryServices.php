<?php

namespace App\Services;

use Log;
use Str;
use Exception;
use App\Models\Attribute;
use App\Repositories\AttributesRepositoryInterface;

class AttributesRepositoryServices implements AttributesRepositoryInterface
{
    public function store($credentials)
    {
        $requested = null;
        if (array_key_exists("requested", $credentials)) {
            $requested = 1;
        }
        try {
            $uuid = Str::uuid();
            $result = Attribute::create([
                'uuid' => $uuid,
                'name' => $credentials['name'],
                'requested' => $requested,
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
    public  function edit($uuid)
    {
        return Attribute::where('uuid', $uuid)->first();
    }
    public  function update($credentials)
    {
        $exist = Attribute::where('name', $credentials['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = Attribute::where('uuid', $credentials['uuid'])->update([
                'name' => $credentials['name']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 200);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public  function delete($uuid)
    {
        // to do
    }
}
