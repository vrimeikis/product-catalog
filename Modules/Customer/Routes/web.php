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
    Route::resource('customers', 'CustomerController');
});
