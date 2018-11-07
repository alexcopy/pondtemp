<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;


Route::get('/receiver/', 'ApiController@tempData');
Route::get('/allfiles/', 'PageController@allCamFiles');
Route::get('/', 'PageController@index');
Route::get('/ping', 'PageController@ping');
Route::get('/graph', 'PageController@graph');
Route::get('/restoredata', 'Controller@restoreData');

Route::get('/allfiles/details', 'PageController@allFilesDetails');

Route::post('/api/v3/getdate', 'ApiController@jsonGraph');

Route::post('/api/v3/dockerhub', 'ApiController@testDockerHub'); // todo delete after test


Route::get('/api/v3/getfilesstats', 'ApiController@filesStat');
Route::post('/api/v3/chemicalanalyse', 'ApiController@chemicalAnalyse');

Route::get('/api/v3/sms', 'ApiController@smsToPusherAPI');
Route::post('/api/v3/sms', 'ApiController@smsToPusherAPI');


Route::group(['prefix' => '/api/v3', 'middleware' => 'auth'], function () {
    Route::get('/stats/today', 'ApiController@getTodayStats');
    Route::get('/stats/total', 'ApiController@getTotalStats');
});

Route::post('webhooks/pusher', [
    'as' => 'pusher.webhooks',
    'uses' => 'PusherController@webhooks'
]);


Route::group(['prefix' => 'pond', 'middleware' => 'auth'], function () {
    Route::resource('/devices', 'Pond\DevicesController');
    Route::resource('/tanks', 'Pond\TanksController');
    Route::resource('/filters', 'Pond\FiltersController');
    Route::resource('/meters', 'Pond\MetersController');
    Route::resource('/chemicals', 'Pond\ChemicalsController');

    Route::resource('/jobs/cleandevice', 'PageController');
    Route::resource('/jobs/meteading', 'PageController');
    Route::resource('/jobs/livestock', 'Pond\LivestocksController');
    Route::resource('/jobs/chemicals', 'PageController');
    Route::resource('/jobs/cleanfilter', 'PageController');
});


Route::resource('cam', 'CamsController');
//Route::post('addcam', 'CamsController@create');

Auth::routes();

Route::get('/home', 'PageController@index')->name('home');
