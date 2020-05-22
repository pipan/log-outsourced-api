<?php

use Illuminate\Support\Facades\Route;

Route::post('logs/{uuid}', 'Api\V1\LogController@single');
Route::post('logs/{uuid}/batch', 'Api\V1\LogController@batch');

Route::prefix('api/v1')->group(function () {
    Route::get('projects', 'Api\V1\ProjectController@index');
    Route::post('projects', 'Api\V1\ProjectController@create');
    Route::get('projects/{uuid}', 'Api\V1\ProjectController@view');
    Route::put('projects/{uuid}', 'Api\V1\ProjectController@update');
    Route::put('projects/{uuid}/generate', 'Api\V1\ProjectController@generateUuid');
    Route::delete('projects/{uuid}', 'Api\V1\ProjectController@delete');

    Route::post('listeners', 'Api\V1\ListenerController@create');
    Route::put('listeners/{uuid}', 'Api\V1\ListenerController@update');
    Route::delete('listeners/{uuid}', 'Api\V1\ListenerController@delete');

    Route::get('handlers', 'Api\V1\HandlerController@index');
    Route::get('handlers/{slug}', 'Api\V1\HandlerController@view');
});
