<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\CriticController;
use App\Http\Controllers\UserController;

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

//Routes du TP2 ici : 
Route::get('/films', 'App\Http\Controllers\FilmController@index');


Route::group(['middleware' => ['throttle:5,1']], function () {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/signin', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/signout', [AuthController::class, 'logout'])->middleware(['throttle:60,1']);
    Route::post('/films', [FilmController::class, 'store'])->middleware(['throttle:60,1']); //Route to create a film
    Route::put('/films/{id}', [FilmController::class, 'update'])->middleware(['throttle:60,1']); //Route to update a film
    Route::delete('/films/{id}', [FilmController::class, 'destroy'])->middleware(['throttle:60,1']); // Route to delete a film
    Route::post('/critic', [CriticController::class, 'store'])->middleware(['throttle:60,1']); //Route to create a critic
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware(['throttle:60,1']); //Route to get a user
    Route::put('/users/{id}', [UserController::class, 'updatePassword'])->middleware(['throttle:60,1']);//Update a user's password
});