<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\Authorization;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\PayementModuleController;
use App\Http\Controllers\Api\V1\ReversementController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\TransactionStatusController;
use App\Http\Controllers\Api\V1\TransactionTypeController;
use App\Http\Controllers\Api\V1\UsersreversementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    ###========== USERs ROUTINGS ========###
    Route::prefix('user')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::any('register', 'Register');
            Route::any('login', 'Login');
            Route::any('active_account', 'AccountActivation');
            Route::middleware(['auth:api'])->get('logout', 'Logout');
            Route::any('all', 'Users');
            Route::any('update', 'UpdateUser');
            Route::any('password/update', 'UpdatePassword');

            Route::any('password/demand_reinitialize', 'DemandReinitializePassword');
            Route::any('password/reinitialize', 'ReinitializePassword');

            Route::any('/{id}/retrieve', 'RetrieveUser');
        });
    });

    ###========== TRANLATIONS ROUTINGS ========###
    Route::prefix('transaction')->group(function () {
        Route::controller(TransactionController::class)->group(function () {
            Route::any('create', 'Create');
            Route::any('all', '_Transactions');
            Route::any('{id}/update', 'Update');
            Route::any('{id}/delete', 'Delete');
            Route::any('/{id}/retrieve', 'Retrieve');
        });
    });

    ###========== CLIENTS ROUTINGS ========###
    Route::prefix('client')->group(function () {
        Route::controller(ClientController::class)->group(function () {
            Route::any('all', '_Clients');
            Route::any('{id}/update', 'Update');
            Route::any('{id}/delete', 'Delete');
            Route::any('/{id}/retrieve', 'Retrieve');
        });
    });

     ###========== REVERSEMENT ROUTINGS ========###
     Route::prefix('reversement')->group(function () {
        Route::controller(ReversementController::class)->group(function () {
            Route::any('create', 'Create');
            Route::any('all', '_Reversements');
            Route::any('{id}/update', 'Update');
            Route::any('{id}/delete', 'Delete');
            Route::any('{id}/retrieve', 'Retrieve');
        });
    });

     ###========== REVERSEMENT CHOICE ROUTINGS ========###
     Route::prefix('choicereversement')->group(function () {
        Route::controller(UsersreversementController::class)->group(function () {
            Route::any('choice', '_Choice');
        });
    });


    Route::any('authorization', [Authorization::class, 'Authorization'])->name('authorization');
});
