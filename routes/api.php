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

    Route::post('login', 'Api\V1\Administrator\LoginController');
    Route::post('invite', 'Api\V1\Administrator\InviteController');
    Route::post('register', 'Api\V1\Administrator\RegisterController');

    Route::get('roles', 'Api\V1\Role\RoleController@index')
        ->name('roles.index');
    Route::post('roles', 'Api\V1\Role\RoleController@create')
        ->name('roles.create');
    Route::get('roles/{uuid}', 'Api\V1\Role\RoleController@view')
        ->name('roles.view');
    Route::delete('roles/{uuid}', 'Api\V1\Role\RoleController@delete')
        ->name('roles.delete');
    Route::put('roles/{uuid}', 'Api\V1\Role\RoleController@update')
        ->name('roles.update');
});
