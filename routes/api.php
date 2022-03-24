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




Route::post('login', 'App\Http\Controllers\API\UserAPIController@login');
Route::post('user/writer', 'App\Http\Controllers\API\UserAPIController@login');
Route::post('user/subscriber', 'App\Http\Controllers\API\UserAPIController@login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('lmao');

Route::group(['middleware' => ['auth:sanctum', 'custom']], function () {
    Route::resource('users', App\Http\Controllers\API\UserAPIController::class);

    Route::resource('posts', PostAPIController::class);
});
