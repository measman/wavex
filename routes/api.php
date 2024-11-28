<?php

use App\Http\Controllers\Api\FeeApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\WalletApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('feetest',FeeApiController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('userinfo', [UserApiController::class, 'userinfo']);
    Route::post('userdelete', [UserApiController::class, 'userdelete']);
    Route::post('useredit', [UserApiController::class, 'useredit']);
    Route::post('userupdate', [UserApiController::class, 'userupdate']);
    Route::get('/transactioninfo', [TransactionApiController::class, 'transactioninfo']);
    Route::post('transactionedit', [TransactionApiController::class, 'transactionedit']);
    Route::post('transactionupdate', [TransactionApiController::class, 'transactionupdate']);
    Route::post('transactiondelete', [TransactionApiController::class, 'transactiondelete']);
    Route::post('transactionsearch', [TransactionApiController::class, 'transactionsearch']);
    Route::get('/walletinfo', [WalletApiController::class, 'walletinfo']);
});
