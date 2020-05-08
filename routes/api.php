<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    Route::post('projects/{hexUuid}/log', 'Api\Vi\LogController@single');
    Route::post('projects/{hexUuid}/log-batch', 'Api\Vi\LogController@batch');

    Route::get('projects', 'Api\V1\ProjectController@index');
    Route::post('projects', 'Api\V1\ProjectController@create');
    Route::get('projects/{hexUuid}', 'Api\V1\ProjectController@view');
    Route::delete('projects/{hexUuid}', 'Api\V1\ProjectController@delete');
});
