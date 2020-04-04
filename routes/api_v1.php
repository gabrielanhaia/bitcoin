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

Route::group([
    'prefix' => 'users',
], function ($router) {
    Route::post('', 'UserController@create');
});

Route::group([
    'prefix' => 'wallets',
    'middleware' => ['auth:api']
], function ($router) {
    Route::post('', 'WalletController@create');
});
