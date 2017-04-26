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



//Route::group(['prefix' => 'api/v1'], function () {
//
//    Route::get('checkchild/{id}', 'ApiController@tempdata');
//
//});

/**
 * Temp Route is valid until platform be reflashed delete afterwards
 */

$app->get('/receiver/', 'ApiController@tempdata');
$app->get('/', 'PageController@index');
$app->get('/ping', 'PageController@ping');
$app->get('/graph', 'PageController@graph');


