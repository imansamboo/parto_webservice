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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/registerCities', function (){
    $cities = App\City::all();
    $count = App\City::count();
   foreach (App\Address::all() as $address){
       $city = $cities[mt_rand(0, $count - 1)];
       $address->city = $city->title;
       $address->province = $city->province->title;
       $address->save();
   }
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
