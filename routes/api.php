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

        Route::get('/sources/{source}/facts', 'SourcesFactsController@index');
        Route::post('/sources/{source}/facts', 'SourcesFactsController@store');

        Route::get('/issues', 'IssuesController@index');
        Route::post('/issues', 'IssuesController@store');

        Route::get('/facts/{fact}/issues', 'FactsIssuesController@index');
        Route::post('/facts/{fact}/issues/{issue}', 'FactsIssuesController@store');
        Route::delete('/facts/{fact}/issues/{issue}', 'FactsIssuesController@destroy');
    });
});
