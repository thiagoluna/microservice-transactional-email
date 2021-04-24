<?php

Route::prefix('v1')->group(function () {
    Route::post('/sendmail', 'Api\\EmailController@sendEmail');
    Route::get('/listemail', 'Api\\EmailController@listEmail');
});
