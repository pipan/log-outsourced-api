<?php

use App\Http\Middleware\ProjectRequired;
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

    Route::post('login', 'Api\V1\Administrator\AuthController@login')
        ->name('auth.login');
    Route::post('refresh', 'Api\V1\Administrator\AuthController@refresh')
        ->name('auth.refresh');
    Route::get('invite/{token}', 'Api\V1\Administrator\InviteController@view')
        ->name('administrator.invite.view');
    Route::post('invite', 'Api\V1\Administrator\InviteController@create')
        ->name('administrator.invite.create');
    Route::post('register', 'Api\V1\Administrator\RegisterController');

    Route::get('roles', 'Api\V1\Role\RoleController@index')
        ->name('roles.index')
        ->middleware(ProjectRequired::class);
    Route::post('roles', 'Api\V1\Role\RoleController@create')
        ->name('roles.create');
    Route::get('roles/{uuid}', 'Api\V1\Role\RoleController@view')
        ->name('roles.view');
    Route::delete('roles/{uuid}', 'Api\V1\Role\RoleController@delete')
        ->name('roles.delete');
    Route::put('roles/{uuid}', 'Api\V1\Role\RoleController@update')
        ->name('roles.update');

    Route::get('users', 'Api\V1\User\UserController@index')
        ->name('users.index')
        ->middleware(ProjectRequired::class);
    Route::post('users', 'Api\V1\User\UserController@create')
        ->name('users.create');
    Route::get('users/{uuid}', 'Api\V1\User\UserController@view')
        ->name('users.view');
    Route::delete('users/{uuid}', 'Api\V1\User\UserController@delete')
        ->name('users.delete');
    Route::put('users/{uuid}', 'Api\V1\User\UserController@update')
        ->name('users.update');
});
