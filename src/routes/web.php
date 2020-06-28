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
$destination = 'PeterLembke\KeyValue\Controllers\TestController@index';
Route::get($route, $destination);

// http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/read/mykey
$route = 'charzam/keyvalue/read/{key?}';
$destination = 'PeterLembke\KeyValue\Controllers\TestController@read';
Route::get($route, $destination);

// http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/write/mykey/mydata
$route = 'charzam/keyvalue/write/{key?}/{value?}';
$destination = 'PeterLembke\KeyValue\Controllers\TestController@write';
Route::get($route, $destination);

// http://aktivbo-api.aktivbo.dev.local/foobar
$route = 'foobar';
$destination = 'PeterLembke\KeyValue\Controllers\AnotherController@index';
Route::get($route, $destination);
