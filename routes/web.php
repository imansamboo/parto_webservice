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
function persian($string) {
    //arrays of persian and latin numbers
    $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $latin_num = range(0, 9);

    $string = str_replace($latin_num, $persian_num, $string);

    return $string;
}
Route::get('/', function () {
    foreach (App\Product::all() as $product){
        $arrayProducts[] = array_merge(
            $product->only(
                [
                    "image",
                    "title",
                    "desc"
                ]
            ),
            $product->prices[0]->only(
                [
                    "oldpricetxt",
                    "pricetxt"
                ]
            ),
            [
                "target" => "viewproduct",
                "targetID" => $product->ID
            ]
        );
    }
    dd($arrayProducts);
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

Route::get('/registerTabs', function (){

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
