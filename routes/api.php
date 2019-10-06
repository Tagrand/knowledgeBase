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
        Route::get('/arguments', 'ArgumentsController@index');
        Route::post('/arguments', 'ArgumentsController@store');
        Route::patch('/arguments/{argument}', 'ArgumentsController@update');

        Route::get('/arguments/{argument}/facts', 'ArgumentsFactsController@index');
        Route::post('/arguments/{argument}/facts/{fact}', 'ArgumentsFactsController@store');
        Route::patch('/arguments/{argument}/facts/{fact}', 'ArgumentsFactsController@update');
        Route::delete('/arguments/{argument}/facts/{fact}', 'ArgumentsFactsController@destroy');

        Route::get('/arguments/{argument}/issues', 'ArgumentsIssuesController@index');
        Route::post('/arguments/{argument}/issues/{issue}', 'ArgumentsIssuesController@store');
        Route::delete('/arguments/{argument}/issues/{issue}', 'ArgumentsIssuesController@destroy');

        Route::get('/authors', 'AuthorsController@index');
        Route::post('/authors', 'AuthorsController@store');

        Route::get('/facts', 'FactsController@index');
        Route::patch('/facts/{fact}', 'FactsController@update');

        Route::get('/facts/{fact}/arguments', 'FactsArgumentsController@index');

        Route::get('/facts/{fact}/issues', 'FactsIssuesController@index');
        Route::post('/facts/{fact}/issues/{issue}', 'FactsIssuesController@store');
        Route::delete('/facts/{fact}/issues/{issue}', 'FactsIssuesController@destroy');

        Route::get('/issues', 'IssuesController@index');
        Route::post('/issues', 'IssuesController@store');
        Route::patch('/issues/{issue}', 'IssuesController@update');

        Route::get('/issues/{issue}/arguments', 'IssuesArgumentsController@index');

        Route::get('/issues/{issue}/facts', 'IssuesFactsController@index');

        Route::get('/sources', 'SourcesController@index');
        Route::post('/sources', 'SourcesController@store');
        Route::patch('/sources/{source}', 'SourcesController@update');

        Route::get('/sources/{source}/arguments', 'SourcesArgumentsController@index');
        Route::post('/sources/{source}/arguments', 'SourcesArgumentsController@store');

        Route::get('/sources/{source}/facts', 'SourcesFactsController@index');
        Route::post('/sources/{source}/facts', 'SourcesFactsController@store');

        Route::get('/sources/{source}/authors', 'SourcesAuthorsController@index');
        Route::post('/sources/{source}/authors/{author}', 'SourcesAuthorsController@store');
        Route::delete('/sources/{source}/authors/{author}', 'SourcesAuthorsController@destroy');
    });
});
