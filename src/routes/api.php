<?php

use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\RemoveReservationController;
use App\Http\Controllers\Api\ReserveController;
use App\Http\Controllers\Api\GetReservationsController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', MeController::class);
    Route::post('/reserve', ReserveController::class);
    Route::post('/removeReserve', RemoveReservationController::class);
    Route::get('/getReserves', GetReservationsController::class);
});