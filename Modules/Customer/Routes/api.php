<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Middleware\ApiPrivateMiddleware;

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

Route::middleware(ApiPrivateMiddleware::class)->namespace('API')->name('api.')->group(function () {
    Route::prefix('auth')->namespace('Auth')->group(function () {
        Route::post('register', 'AuthenticationController@register')->name('register');
        Route::post('login', 'AuthenticationController@login')->name('login');

        Route::middleware('auth:api')->group(function () {
            Route::post('logout', 'AuthenticationController@logout')->name('logout');
            Route::get('me', 'AuthenticationController@me')->name('me');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('customer', 'CustomerController@show')->name('customer.show');
        Route::put('customer', 'CustomerController@update')->name('customer.update');
        Route::delete('customer', 'CustomerController@destroy')->name('customer.destroy');
    });

});
