<?php

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
    Route::post('', 'UserController@create')->name('users.create');
});

Route::group([
    'prefix' => 'wallets',
    'middleware' => ['auth:api']
], function ($router) {
    Route::post('', 'WalletController@create')->name('wallets.create');
    Route::get('{wallet_address}/transactions', 'TransactionController@listTransactionsByWallet')
        ->name('wallets.list.transactions');
});

Route::group([
    'prefix' => 'transactions',
    'middleware' => ['auth:api']
], function ($router) {
    Route::post('', 'TransactionController@makeTransfer')->name('transactions.create');
    Route::get('', 'TransactionController@listTransaction')->name('transactions.list');
});
