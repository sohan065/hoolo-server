<?php


use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserAccessToken;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AppRegistrationController;



// user registration from old website
Route::post('user/reg', function (Request $request) {
    $phone = $request->phone;
    $exist = User::where('phone', $phone)->first();
    if (!$exist) {
        $exist = User::create([
            'uuid' => Str::uuid(),
            'phone' => $phone,
            'type' => 0,
        ]);
    }


    if ($exist) {

        $exist = User::where('phone', $phone)->first();
        $os = Agent::platform();
        $browser = Agent::browser();
        $macAddress = "macaddress";
        $ipAddress = request()->ip();
        $tokenData = [
            'uuid' => $exist->uuid,
            'phone' => $exist->phone,
            'os' => $os,
            'browser' => $browser,
            'mac_address' => $macAddress,
            'ip_address' =>  $ipAddress,
            'type' => $exist->type,
            'time' => Carbon::now()
        ];
        $token = Token::create($tokenData);
        if ($token) {
            try {
                $result = UserAccessToken::updateOrCreate(
                    ['user_uuid' => $exist->uuid, 'os' => $os, 'browser' => $browser, 'mac_address' => $macAddress],
                    ['token' => $token, 'ip_address' => $ipAddress]
                );
            } catch (Exception $e) {
                log::error($e);
                $result = false;
            }
            if ($result) {
                return response(['token' => $token, 'uuid' => $exist->uuid, 'type' => $exist->type], 202);
            }
        }
    }
});


// basic routes for app registration
Route::post('app/register', [AppRegistrationController::class, 'register']);


Route::group(
    ['prefix' => 'product/card', 'as' => 'product/card'],
    function () {
        Route::post('success', [ProductController::class, 'productCardSuccess']);
        Route::post('fail', [ProductController::class, 'productCardFail']);
        Route::post('cancel', [ProductController::class, 'productCardCancel']);
    }
);

Route::group(
    ['prefix' => 'course/card', 'as' => 'course/card'],
    function () {
        Route::post('success', [CourseController::class, 'courseCardSuccess']);
        Route::post('fail', [CourseController::class, 'courseCardFail']);
        Route::post('cancel', [CourseController::class, 'courseCardCancel']);
    }
);