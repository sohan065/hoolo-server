<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Repositories\SmsRepositoryInterface;

class SmsRepositoryServices implements SmsRepositoryInterface
{

    public function sendSms($credentials)
    {
        $sendTo = $credentials['contacts'];
        $url = env('MIM_SMS_URL');
        try {
            $result =  Http::get($url, [
                'sendsms' => 'test',
                'apikey' =>  env('MIM_SMS_API_KEY'),
                'apitoken' => '0vcT1668930117',
                'type' => 'sms',
                'from' => env('MIM_SMS_SENDER_ID'),
                'to' => $sendTo,
                'text' => $credentials['msg'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        return $result;
    }
}
