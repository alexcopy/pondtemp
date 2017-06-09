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


$app->get('/receiver/', 'ApiController@tempData');
$app->get('/allfiles/', 'PageController@allCamFiles');
$app->get('/', 'PageController@index');
$app->get('/ping', 'PageController@ping');
$app->get('/graph', 'PageController@graph');
$app->get('/restoredata', 'Controller@restoreData');

$app->get('/allfiles/details', 'PageController@allFilesDetails');
$app->post('/api/v3/getdate', 'ApiController@jsonGraph');

$app->post('/api/v3/getfilesstats', 'ApiController@filesStat');
$app->post('/api/v3/chemicalanalyse', 'ApiController@chemicalAnalyse');


