<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** API */
$router->get('near-store', 'App\Http\Controllers\ApiController@NearStore');
$router->post('game-apply', 'App\Http\Controllers\ApiController@GameApply');
$router->post('game-result', 'App\Http\Controllers\ApiController@GameResult');
$router->post('prize-list', 'App\Http\Controllers\ApiController@PrizeList');

//$router->get('importcsv', 'ExampleController@importcsv');
