<?php

namespace App\Repositories;

interface MailRepositoryInterface
{
    public function send($subject, $data, $email, $view);
}
