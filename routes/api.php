<?php

use Illuminate\Support\Facades\Route;

Route::post('log/{hexUuid}', 'Api\Vi\LogController@single');
Route::post('log/{hexUuid}/batch', 'Api\Vi\LogController@batch');

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
    // Route::post('handlers', 'Api\V1\HandlerController@create');
    // Route::put('handlers/{hexUuid}', 'Api\V1\HandlerController@update');
    // Route::delete('handlers/{hexUuid}', 'Api\V1\HandlerController@delete');

    Route::get('settings/{hexUuid}', 'Api\V1\SettingsController@view');

    Route::get('config', 'Api\V1\ConfigController@index');
    Route::put('config', 'Api\V1\ConfigController@update');
});
