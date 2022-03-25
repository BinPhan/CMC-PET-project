<?php

use App\Http\Controllers\API\PostAPIController;

use Illuminate\Http\Request;
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

Route::post('login', 'UserAPIController@login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/hello', function () {
    return "HELLO";
});

Route::post('/login', 'App\Http\Controllers\AuthController@login')->name('users.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('posts', PostAPIController::class);
    Route::resource('users', App\Http\Controllers\API\UserAPIController::class);
});

