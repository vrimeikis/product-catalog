<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function() {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')
    ->name('home');

Route::prefix('products')->name('products.')->group(function() {
    Route::get('/', 'ProductController@index')
        ->name('index');
    Route::get('create', 'ProductController@create')
        ->name('create');
    Route::post('/', 'ProductController@store')
        ->name('store');
    Route::get('{product}/edit', 'ProductController@edit')
        ->name('edit');
    Route::put('{product}', 'ProductController@update')
        ->name('update');
    Route::delete('{product}', 'ProductController@destroy')
        ->name('destroy');

    Route::resource('attributes', 'ProductAttributeController')
        ->except(['show']);
});

Route::prefix('categories')->name('categories.')->group(function() {
    Route::get('/', 'CategoryController@index')
        ->name('index');
    Route::get('create', 'CategoryController@create')
        ->name('create');
    Route::post('/', 'CategoryController@store')
        ->name('store');
    Route::get('{category}/edit', 'CategoryController@edit')
        ->name('edit');
    Route::put('{category}', 'CategoryController@update')
        ->name('update');
    Route::delete('{category}', 'CategoryController@destroy')
        ->name('destroy');
});


