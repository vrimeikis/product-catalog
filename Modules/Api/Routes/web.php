<?php

declare(strict_types = 1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Administration\Http\Middleware\RouteAccessMiddleware;

Route::middleware(['auth:admin', RouteAccessMiddleware::ALIAS])->namespace('Admin')->group(function () {
    Route::resource('api-keys', 'ApiController')->names([
        'index' => 'api_keys.index',
        'create' => 'api_keys.create',
        'store' => 'api_keys.store',
        'show' => 'api_keys.show',
        'edit' => 'api_keys.edit',
        'update' => 'api_keys.update',
        'destroy' => 'api_keys.destroy',
    ]);
});
