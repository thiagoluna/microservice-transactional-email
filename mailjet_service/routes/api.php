<?php

Route::prefix('v1')->group(function () {
    Route::post('/sendemail', 'Api\SendEmailController@sendEmail');
});
