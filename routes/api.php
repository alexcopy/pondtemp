<?php

use App\Http\Controllers\ApiController;
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
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getMeters', [ApiController::class, 'getMeters']);
Route::get('/getPonds', [ApiController::class, 'getPonds']);
Route::get('/getTypes', [ApiController::class, 'getTypes']);
Route::get('/metersData', [ApiController::class, 'metersData']);
Route::get('/getFeeds', [ApiController::class, 'getFeeds']);
