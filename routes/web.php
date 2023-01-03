<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/addClient', 'App\Http\Controllers\ClientController@store');
Route::get('/listClients', 'App\Http\Controllers\ClientController@list');
Route::put('/updateClient', 'App\Http\Controllers\ClientController@update');
Route::post('/addPayment', 'App\Http\Controllers\PaymentController@store');
Route::get('/listClientPayments/{id}', 'App\Http\Controllers\PaymentController@getClientPayments');
