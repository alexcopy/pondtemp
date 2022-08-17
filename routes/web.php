<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Pond\DevicesController;
use App\Http\Controllers\Pond\DeviceTypesController;
use App\Http\Controllers\Pond\FeedController;
use App\Http\Controllers\Pond\FiltersController;
use App\Http\Controllers\Pond\LivestocksController;
use App\Http\Controllers\Pond\MetersController;
use App\Http\Controllers\Pond\TanksController;
use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/home', [PageController::class, 'index'])->name('home');


Route::get('/receiver/', 'ApiController@tempData');
Route::get('/allfiles/', [PageController::class, 'allCamFiles']);
Route::get('/', [PageController::class, 'index']);
Route::get('/ping', [PageController::class, 'ping']);
Route::get('/graph', [PageController::class, 'graph']);
Route::get('/restoredata', [Controller::class, 'restoreData']);

Route::get('/allfiles/details', [PageController::class, 'allFilesDetails']);

Route::post('/api/v3/getdate', [ApiController::class, 'jsonGraph']);


Route::get('/api/v3/getfilesstats', [ApiController::class, 'filesStat']);
Route::post('/api/v3/chemicalanalyse', [ApiController::class, 'chemicalAnalyse']);

Route::get('/api/v3/sms', [ApiController::class, 'smsToPusherAPI']);
Route::post('/api/v3/sms', [ApiController::class, 'smsToPusherAPI']);


Route::group(['prefix' => '/api/v3', 'middleware' => 'auth'], function () {
    Route::get('/stats/today', [ApiController::class, 'getTodayStats']);
    Route::get('/stats/total', [ApiController::class, 'getTotalStats']);
});

Route::post('webhooks/pusher', [
    'as' => 'pusher.webhooks',
    'uses' => [PusherController::class, 'webhooks']
]);

Route::resource('cam', 'CamsController');

Route::group(['prefix' => 'pond', 'middleware' => 'auth'], function () {
    Route::resource('/devices', DevicesController::class);
    Route::resource('/types', DeviceTypesController::class);
    Route::resource('/tanks', TanksController::class);
    Route::resource('/filters', FiltersController::class);
    Route::resource('/meters', MetersController::class);
    Route::resource('/chemicals', 'Pond\ChemicalsController');
    Route::resource('/feed', FeedController::class);


    Route::delete('/meters', [MetersController::class, 'destroy']);
    Route::post('/meters/submit', [MetersController::class, 'metersSubmit']);
    Route::put('/feed', [FeedController::class, 'create']);
    Route::delete('/feed', [FeedController::class, 'destroy']);
    Route::resource('/jobs/livestock', LivestocksController::class);


    Route::resource('/jobs/meteading', PageController::class);
    Route::resource('/jobs/chemicals', PageController::class);
    Route::resource('/jobs/cleanfilter', PageController::class);
});
Auth::routes();


