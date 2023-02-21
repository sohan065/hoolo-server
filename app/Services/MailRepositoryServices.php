<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Repositories\MailRepositoryInterface;

class MailRepositoryServices implements MailRepositoryInterface
{
    public function send($subject, $data, $email, $view)
    {
        try {
            Mail::send($view, $data, function ($message) use ($subject, $email) {
                $message->to($email);
                $message->subject($subject);
            });
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
