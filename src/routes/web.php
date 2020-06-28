<?php

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

// http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue
$route = 'charzam/keyvalue';
$destination = 'charzam\keyvalue\Controllers\TestController@index';
Route::get($route, $destination);

// http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/read/mykey
$route = 'charzam/keyvalue/read/{key?}';
$destination = 'charzam\keyvalue\Controllers\TestController@read';
Route::get($route, $destination);

// http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/write/mykey/mydata
$route = 'charzam/keyvalue/write/{key?}/{value?}';
$destination = 'charzam\keyvalue\Controllers\TestController@write';
Route::get($route, $destination);

// http://aktivbo-api.aktivbo.dev.local/foobar
$route = 'foobar';
$destination = 'charzam\keyvalue\Controllers\AnotherController@index';
Route::get($route, $destination);
