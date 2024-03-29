<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\FilterFlashController;
use App\Http\Controllers\PondPumpStatsController;
use App\Http\Controllers\PondSwitchController;
use App\Http\Controllers\PondWeatherController;
use App\Http\Controllers\PowerDeviceController;
use App\Http\Controllers\SolarController;
use App\Http\Controllers\SolarPowerController;
use App\Http\Controllers\WaterTempController;
use App\Http\Middleware\VerifyCsrfToken;
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


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/getMeters', [ApiController::class, 'getMeters']);
Route::get('/getPonds', [ApiController::class, 'getPonds']);
Route::get('/getTypes', [ApiController::class, 'getTypes']);
Route::get('/metersData', [ApiController::class, 'metersData']);
Route::get('/getFeeds', [ApiController::class, 'getFeeds']);


Route::resource('solarpower', SolarPowerController::class);
Route::resource('solar', SolarController::class);
Route::resource('watertemp', WaterTempController::class);
Route::resource('fflash', FilterFlashController::class);
Route::resource('pondpump', PondPumpStatsController::class);
Route::resource('pondswitch', PondSwitchController::class);
Route::resource('pondweather', PondWeatherController::class);
Route::resource('power_averages', PowerDeviceController::class);
