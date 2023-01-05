<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('addClient', 'App\Http\Controllers\ClientController@store');
Route::get('listClients', 'App\Http\Controllers\ClientController@list');
Route::put('updateClient', 'App\Http\Controllers\ClientController@update');
Route::post('addPayment', 'App\Http\Controllers\PaymentController@store');
Route::get('listClientPayments/{id}', 'App\Http\Controllers\PaymentController@getClientPayments');
Route::delete('deleteClient/{id}', 'App\Http\Controllers\ClientController@delete');
Route::delete('deletePayment/{id}', 'App\Http\Controllers\PaymentController@deletePayment');
