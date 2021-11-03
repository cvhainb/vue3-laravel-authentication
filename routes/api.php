<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function() {

    /**
     * Frontend
     */
    Route::group(['prefix' => 'storefront', 'middleware' => ['throttle:60,1']], function() {

        Route::post('login', 'V1\Storefront\AuthController@accountLogin');
        Route::post('register', 'V1\Storefront\AuthController@accountRegister');

        Route::group(['prefix' => 'user', 'middleware' => ['auth:api', 'throttle:60,1']], function() {
            Route::get('profile', 'V1\Storefront\UserController@index');
            Route::post('update-profile', 'V1\Storefront\UserController@update');
        });

        Route::group(['prefix' => 'product', 'middleware' => ['auth:api', 'throttle:60,1']], function() {
            Route::get('syncing', 'V1\Storefront\ProductController@syncing');
            Route::get('all', 'V1\Storefront\ProductController@index');
        });

        Route::group(['prefix' => 'export', 'middleware' => ['auth:api', 'throttle:60,1']], function() {
            Route::post('datafeed', 'V1\Storefront\ExportController@index');
        });

        Route::group(['prefix' => 'oauth2', 'middleware' => ['auth:api', 'throttle:60,1']], function() {
            Route::post('google', 'V1\Storefront\OAuth2Controller@google');
        });

        Route::group(['prefix' => 'google', 'middleware' => ['auth:api', 'throttle:60,1']], function() {
            Route::get('authinfo', 'V1\Storefront\GoogleMerchantController@authinfo');
        });

    });
    
});