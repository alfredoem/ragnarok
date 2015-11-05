<?php
/*
|--------------------------------------------------------------------------
| Package Routes
|--------------------------------------------------------------------------
*/

Route::controller('auth', 'Auth\AuthController');

Route::group(['prefix' => 'ragnarok/api/v1', 'middleware' => 'api_guard'], function(){
    Route::controller('/', 'RagnarokApi\v1\RagnarokApiController');
});