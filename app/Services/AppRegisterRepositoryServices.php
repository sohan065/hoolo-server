<?php

namespace App\Services;


use Token;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\RegisteredApp;
use Illuminate\Support\Facades\Log;
use App\Repositories\AppRegisterRepositoryInterface;

class AppRegisterRepositoryServices implements AppRegisterRepositoryInterface
{
    public function registration($credentials)
    {
        $uuid = Str::uuid();
        $expDate = Carbon::now()->addDays(365);
        $app_key = Token::create([
            'uuid' => $uuid,
            'app_name' => $credentials['app_name'],
            'email' =>  $credentials['email'],
            'platform' =>  $credentials['platform'],
            'exp_date' => $expDate,
        ]);

        if ($app_key) {
            try {
                $result = RegisteredApp::create([
                    'uuid' => $uuid,
                    'app_name' => $credentials['app_name'],
                    'domain' => $credentials['domain'],
                    'email' =>  $credentials['email'],
                    'phone' =>  $credentials['phone'],
                    'platform' =>  $credentials['platform'],
                    'app_key' =>  $app_key,
                ]);
            } catch (Exception $e) {
                Log::error($e);
                $result = false;
            }
        }
        if ($result) {
            return response(['message' => 'success', 'app_key' => $app_key], 201);
        }
        return response(['message' => 'failed'], 406);
    }
    public function active($uuid)
    {
        try {
            $result = RegisteredApp::where('uuid', $uuid)->update(['status' => 1]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'failed'], 406);
    }
    public function inactive($uuid)
    {
        try {
            $result = RegisteredApp::where('uuid', $uuid)->update(['status' => 0]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 202);
        }
        return response(['message' => 'failed'], 406);
    }
    public function delete($uuid)
    {
        try {
            $result = RegisteredApp::where('uuid', $uuid)->delete();
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 410);
        }
        return response(['message' => 'failed'], 406);
    }
}
