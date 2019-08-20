<?php

use Illuminate\Http\Request;

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

Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('/sources', 'SourcesController@index');
        Route::post('/sources', 'SourcesController@store');

        Route::post('/sources/{source}/facts', 'SourcesFactsController@create');
    });
});
