<?php

namespace App\Repositories;

interface InstructorRepositoryInterface
{
    public function getAllInstructor();
    public function instructorReg($credentials);
    public function otpVerify($credentials);
    public function resendOtpCode($credentials);
    public function createInstructorInfo($credentials, $token);

    public function createInstructorDetail($credentials, $certification, $token);
    public function storeFeatured($credentils);
    public function getAllFeaturedInstructor();
    public function deleteFeatured($credentils);
}
