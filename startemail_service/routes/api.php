<?php

Route::prefix('v1')->group(function () {
    Route::post('/sendemail', 'Api\\EmailController@sendEmail');
    Route::get('/listemail', 'Api\\EmailController@listAll');
});
