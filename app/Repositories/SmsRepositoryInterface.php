<?php

namespace App\Repositories;

interface SmsRepositoryInterface
{

    public function sendSms($credentials);
}
