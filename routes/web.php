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


/**
 * Temp Route is valid until platform be reflashed delete afterwards
 */

$app->get('/receiver/', 'ApiController@tempData');
$app->get('/', 'PageController@index');
$app->get('/ping', 'PageController@ping');
$app->get('/graph', 'PageController@graph');
$app->post('/api/v3/getdate', 'ApiController@dateInterval');


