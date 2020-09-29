<?php

namespace App\Providers;

use App\Repository\Database\Administrator\AdministratorDatabaseRepository;
use App\Repository\Database\Listener\ListenerDatabaseRepository;
use App\Repository\Database\Project\ProjectDatabaseRepository;
use App\Repository\Database\Role\RoleDatabaseRepository;
use App\Repository\Database\User\UserDatabaseRepository;
use App\Repository\Eloquent\Role\RoleEloquentRepository;
use App\Repository\File\Administrator\AdministratorFileRepository;
use App\Repository\File\Listener\ListenerFileRepository;
use App\Repository\File\Project\ProjectFileRepository;
use App\Repository\File\Role\RoleFileRepository;
use App\Repository\Memory\Handler\HandlerMemoryRepository;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Exception;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $repositories = [
            'file' => new SimpleRepository(
                new ProjectFileRepository(),
                new ListenerFileRepository(),
                new HandlerMemoryRepository(),
                new AdministratorFileRepository(),
                new RoleFileRepository()
            ),
            'database' => new SimpleRepository(
                new ProjectDatabaseRepository(),
                new ListenerDatabaseRepository(),
                new HandlerMemoryRepository(),
                new AdministratorDatabaseRepository(),
                new RoleDatabaseRepository(),
                new UserDatabaseRepository()
            )
        ];
        $repositotyType = env('REPOSITORY_TYPE', 'file');
        if (!isset($repositories[$repositotyType])) {
            throw new Exception("Repository type is not supported: " . $repositotyType);
        }

        $this->app->instance(Repository::class, $repositories[$repositotyType]);
    }

    public function boot()
    {
        
    }
}
