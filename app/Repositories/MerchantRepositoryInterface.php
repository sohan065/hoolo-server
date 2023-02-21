<?php

namespace App\Repositories;

interface MerchantRepositoryInterface
{
    public function storeMerchant($credentials);
    public function registration($credentials, $company_logo, $company_banner);
    public function verification($credentials);
    public function resendVerification($email);
    public function attempt($credentials);
    public function forgetPassword($credentials);
    public function resetPassword($credentials);
    public function changePassword($credentials, $header);
    public function profile($token, $file);
    public function storeInfo($credentials, $uuid, $logo, $banner);
    public function featured($merchant_uuid);
}
