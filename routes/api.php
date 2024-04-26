<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/logout', [AuthController::class, 'logout']);
    });

    Route::middleware(['verifyToken'])->group(function () {
        Route::get('/buses', [BusController::class, 'index']);
        Route::post('/buses', [BusController::class, 'create']);
        Route::put('/buses/{bus_id}', [BusController::class, 'update']);
        Route::delete('/buses/{bus_id}', [BusController::class, 'delete']);

        Route::get('/drivers', [DriverController::class, 'index']);
        Route::post('/drivers', [DriverController::class, 'create']);
        Route::put('/drivers/{driver_id}', [DriverController::class, 'update']);
        Route::delete('/drivers/{driver_id}', [DriverController::class, 'delete']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'create']);
        Route::delete('/orders/{order_id}', [OrderController::class, 'delete']);
    });
});
