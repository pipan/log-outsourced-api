<?php

use Illuminate\Support\Facades\Route;

Route::post('log/{hexUuid}', 'Api\Vi\LogController@single');
Route::post('log/{hexUuid}/batch', 'Api\Vi\LogController@batch');

Route::prefix('api/v1')->group(function () {
    Route::get('projects', 'Api\V1\ProjectController@index');
    Route::post('projects', 'Api\V1\ProjectController@create');
    Route::get('projects/{hexUuid}', 'Api\V1\ProjectController@view');
    Route::put('projects/{hexUuid}', 'Api\V1\ProjectController@update');
    Route::put('projects/{hexUuid}/generate', 'Api\V1\ProjectController@generateUuid');
    Route::delete('projects/{hexUuid}', 'Api\V1\ProjectController@delete');

    Route::post('handlers', 'Api\V1\HandlerController@create');
    Route::put('handlers/{hexUuid}', 'Api\V1\HandlerController@update');
    Route::delete('handlers/{hexUuid}', 'Api\V1\HandlerController@delete');

    Route::get('settings', 'Api\V1\SettingsController@index');
});
