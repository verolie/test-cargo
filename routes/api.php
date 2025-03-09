<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderShipmentController;
use App\Http\Controllers\Api\OrderTrackingController;

//USER
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/check-email', [AuthController::class, 'checkEmail']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);

    //Order Shipment
    Route::get('/order-shipment', [OrderShipmentController::class, 'index']);
    Route::post('/order-shipment', [OrderShipmentController::class, 'store']);
    Route::put('/order-shipment/{id}', [OrderShipmentController::class, 'update']);
    Route::delete('/order-shipment/{id}', [OrderShipmentController::class, 'destroy']);
});
