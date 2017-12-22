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
    return redirect()->route('simulation.index');
});

Route::get('/home', function () {
    return redirect()->route('simulation.index');
});

Auth::routes();

Route::get('/simulation', 'SimulationController@index')->name('simulation.index')->middleware('auth');

Route::get('/users/{user}', 'UserController@edit')->name('user.edit')->middleware('auth');
Route::post('/users/{user}', 'UserController@update')->name('user.update')->middleware('auth');

Route::get('/coinbase/dashboard', 'CoinbaseController@dashboard')->name('coinbase.dashboard')->middleware('auth');

Route::get('/coinbase/primaryaccount', 'CoinbaseController@primaryAccount')->name('coinbase.primaryaccount');
Route::get('/coinbase/spotprice', 'CoinbaseController@spotPrice')->name('coinbase.spotprice');
Route::get('/coinbase/sellprice', 'CoinbaseController@sellPrice')->name('coinbase.sellprice');
Route::get('/coinbase/buyprice', 'CoinbaseController@buyPrice')->name('coinbase.buyprice');
Route::get('/coinbase/money', 'CoinbaseController@money')->name('coinbase.money');
Route::get('/coinbase/lastbuy', 'CoinbaseController@lastBuy')->name('coinbase.lastbuy');
Route::get('/coinbase/lastsell', 'CoinbaseController@lastSell')->name('coinbase.lastsell');