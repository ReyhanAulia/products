<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MidtransController;

Route::get('midtrans/debug', function() {
    return response()->json(['message' => 'Route is working']);
});

Route::post('midtrans/transaction', [MidtransController::class, 'createTransaction']);
Route::post('/payment/finish', [MidtransController::class, 'handleFinish']);
Route::post('/payment/unfinish', [MidtransController::class, 'handleUnfinish']);
Route::post('/payment/error', [MidtransController::class, 'handleError']);



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('products', ProductController::class);




