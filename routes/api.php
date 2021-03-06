<?php

use App\Http\Middleware\AuthRequired;
use App\Http\Middleware\ProjectRequired;
use Illuminate\Support\Facades\Route;

Route::post('logs/{key}', 'Api\V1\Log\LogController@singleWithKey');
Route::post('logs/{key}/batch', 'Api\V1\Log\LogController@batchWithKey');
Route::get('permissions/{key}', 'Api\V1\Permission\PermissionValidateController');

Route::prefix('api/v1')
    ->middleware(AuthRequired::class)
    ->group(function () {
        Route::post('logs/single', 'Api\V1\Log\LogController@singleApi')
            ->middleware(ProjectRequired::class)
            ->name('api.logs.single');
        Route::post('logs/batch', 'Api\V1\Log\LogController@batchApi')
            ->middleware(ProjectRequired::class)
            ->name('api.logs.batch');

        Route::get('projects', 'Api\V1\Project\ProjectController@index')
            ->name('projects.index');
        Route::post('projects', 'Api\V1\Project\ProjectController@create')
            ->name('projects.create');
        Route::get('projects/{uuid}', 'Api\V1\Project\ProjectController@view')
            ->name('projects.view');
        Route::put('projects/{uuid}', 'Api\V1\Project\ProjectController@update')
            ->name('projects.update');
        Route::delete('projects/{uuid}', 'Api\V1\Project\ProjectController@delete')
            ->name('projects.delete');

        Route::put('projects/{uuid}/generate', 'Api\V1\Project\ProjectUuidController@generate')
            ->name('projects.generate');

        Route::get('listeners', 'Api\V1\Listener\ListenerController@index')
            ->middleware(ProjectRequired::class)
            ->name('listeners.index');
        Route::post('listeners', 'Api\V1\Listener\ListenerController@create')
            ->name('listeners.create');
        Route::put('listeners/{uuid}', 'Api\V1\Listener\ListenerController@update')
            ->name('listeners.update');
        Route::delete('listeners/{uuid}', 'Api\V1\Listener\ListenerController@delete')
            ->name('listeners.delete');

        Route::get('handlers', 'Api\V1\Handler\HandlerController@index')
            ->name('handlers.index');
        Route::get('handlers/{slug}', 'Api\V1\Handler\HandlerController@view')
            ->name('handlers.view');

        Route::delete('administrators/{uuid}', 'Api\V1\Administrator\AdministratorController@delete')
            ->name('administrators.delete');
        Route::get('administrators', 'Api\V1\Administrator\AdministratorController@index')
            ->name('administrators.index');
        Route::post('administrators/invite', 'Api\V1\Administrator\InviteController@create')
            ->name('administrators.invite.create');

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

        Route::get('permissions', 'Api\V1\Permission\PermissionController@index')
            ->name('permissions.index')
            ->middleware(ProjectRequired::class);
        Route::post('permissions', 'Api\V1\Permission\PermissionController@create')
            ->name('permissions.create');

        Route::get('settings/defaultroles', 'Api\V1\Settings\DefaultRole\DefaultRoleController@load')
            ->name('settings.defaultroles.load')
            ->middleware(ProjectRequired::class);
        Route::post('settings/defaultroles', 'Api\V1\Settings\DefaultRole\DefaultRoleController@save')
            ->name('settings.defaultroles.save');

        Route::get('settings/projectkeys', 'Api\V1\Settings\ProjectKey\ProjectKeyController@index')
            ->name('settings.projectkeys.index')
            ->middleware(ProjectRequired::class);
        Route::post('settings/projectkeys', 'Api\V1\Settings\ProjectKey\ProjectKeyController@create')
            ->name('settings.projectkeys.create');
        Route::delete('settings/projectkeys/{uuid}', 'Api\V1\Settings\ProjectKey\ProjectKeyController@delete')
            ->name('settings.projectkeys.delete');
        Route::put('settings/projectkeys/{uuid}', 'Api\V1\Settings\ProjectKey\ProjectKeyController@update')
            ->name('settings.projectkeys.update');
    });

Route::prefix('api/v1')->group(function () {
    Route::post('auth/access', 'Api\V1\Administrator\AuthController@access')
        ->name('auth.access');
    Route::post('auth/refresh', 'Api\V1\Administrator\AuthController@refresh')
        ->name('auth.refresh');
    Route::get('administrators/invite/{token}', 'Api\V1\Administrator\InviteController@view')
        ->name('administrators.invite.view');
    Route::post('register', 'Api\V1\Administrator\RegisterController');
});
