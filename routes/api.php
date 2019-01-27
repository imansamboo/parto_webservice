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
Route::resource('products', 'Api\\ProductsController', ['except' => ['create', 'edit']]);
Route::resource('favors', 'Api\\ProductFavorsController', ['except' => ['create', 'edit']]);
Route::resource('procats', 'Api\\ProductCategoriesController', ['except' => ['create', 'edit']]);
Route::resource('features', 'Api\\ProductFeaturesController', ['except' => ['create', 'edit']]);
Route::resource('menus', 'Api\\ProductMenusController', ['except' => ['create', 'edit']]);
Route::resource('prices', 'Api\\ProductPricesController', ['except' => ['create', 'edit']]);
Route::resource('slides', 'Api\\ProductSlidesController', ['except' => ['create', 'edit']]);
Route::resource('tabs', 'Api\\TabsController', ['except' => ['create', 'edit']]);
Route::get('provinces/{id}/getCities', 'Api\\ProvincesController@indexCities');
Route::get('provinces/{id}/getAddresses', 'Api\\ProvincesController@indexAddresses');
Route::get('cities/{id}/getAddresses', 'Api\\CitiesController@indexAddresses');
Route::post('getProvince', 'Api\\CitiesController@getProvince');
Route::get('addresses/{id}/getProvince', 'Api\\AddressesController@getProvince');
