<?php

use App\Http\Controllers\FavoritequoteController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConsumeApiController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login-api', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::get('quotes', [ConsumeApiController::class, 'index']);
    Route::post('search-quotes', [ConsumeApiController::class, 'search']);
    Route::apiResource('favoritequotes', FavoritequoteController::class);
});


Route::group(['middleware' => ['auth:sanctum', 'admin']], function(){
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/user/{id}', [UserController::class, 'show']);
    Route::patch('/admin/user/{id}', [UserController::class, 'banning']);
    Route::put('/admin/user', [UserController::class, 'update']);
});









