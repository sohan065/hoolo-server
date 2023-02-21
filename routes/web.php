<?php

use App\Jobs\PurchaseNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'team.hoolo.live';
});
Route::get('send', function () {
    $contact = '8801750813285';
    $msg = 'This is test job';
    dispatch(new PurchaseNotification($contact, $msg));
    dd('measse has been sent');
});
