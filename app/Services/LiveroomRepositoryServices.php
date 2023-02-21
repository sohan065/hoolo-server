<?php

namespace App\Services;

use Log;
use Str;
use Sms;
use Exception;
use App\Models\User;
use App\Models\LiveRoom;
use App\Models\LiveSteramingOtp;
use App\Repositories\LiveroomRepositoryInterface;

class LiveroomRepositoryServices implements LiveroomRepositoryInterface
{
    public function test()
    {
        return "test";
    }
}
