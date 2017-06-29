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

Route::post('/api/v3/getfilesstats', 'ApiController@filesStat');
Route::post('/api/v3/chemicalanalyse', 'ApiController@chemicalAnalyse');


