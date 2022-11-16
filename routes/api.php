<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BankAccountController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\TransactionController;
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
    'namespace' => 'API',
], function () {

    // ======================================
    //     AUTHENTICATION ROUTE
    // ======================================
    Route::post('register', [AuthController::class, 'register'])->name('api.register');
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:3,2')->name('api.login');

    // ======================================
    // Grouping the Authenticated route
    // ======================================
    Route::group([
        'namespace' => '\\',
        'middleware' => ['auth:api', 'scopes:user-side'],
    ], function () {

        Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');

        // Customers
        Route::get('customers', [CustomerController::class, 'index'])->name('api.customers.index');
        Route::post('customers', [CustomerController::class, 'store'])->name('api.customers.store');
        Route::get('customers/{customerId}/bank-accounts', [CustomerController::class, 'getBankAccounts'])
            ->name('api.customers.bank-accounts');

        // bank accounts
        Route::get('bank-accounts', [BankAccountController::class, 'index'])
            ->name('api.bank-accounts.index');
        Route::post('bank-accounts', [BankAccountController::class, 'store'])
            ->name('api.bank-accounts.store');
        Route::get('bank-accounts/{account_number}/balance', [BankAccountController::class, 'getBalance'])
            ->name('api.bank-accounts.balance');

        // transactions
        Route::group([
            'prefix' => 'bank-accounts/{account_number}'
        ], function () {
            Route::get('history', [TransactionController::class, 'index'])
                ->name('api.transaction.index');
            Route::post('transfer', [TransactionController::class, 'transfer'])
                ->name('api.transaction.transfer');
        });
    });

});
