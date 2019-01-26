<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('posts', 'Api\\PostsController', ['except' => ['create', 'edit']]);
Route::resource('addresses', 'Api\\AddressesController', ['except' => ['create', 'edit']]);
Route::resource('provinces', 'Api\\ProvincesController', ['except' => ['create', 'edit']]);
Route::resource('cities', 'Api\\CitiesController', ['except' => ['create', 'edit']]);
Route::resource('addresses', 'Api\\AddressesController', ['except' => ['create', 'edit']]);
Route::get('provinces/{id}/getCities', 'Api\\ProvincesController@indexCities');
Route::get('provinces/{id}/getAddresses', 'Api\\ProvincesController@indexAddresses');
Route::get('cities/{id}/getAddresses', 'Api\\CitiesController@indexAddresses');
Route::post('getProvince', 'Api\\CitiesController@getProvince');
Route::get('addresses/{id}/getProvince', 'Api\\AddressesController@getProvince');
