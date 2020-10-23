<?php

namespace App\Providers;

use App\Repository\Database\Administrator\AdministratorDatabaseRepository;
use App\Repository\Database\Listener\ListenerDatabaseRepository;
use App\Repository\Database\Permission\PermissionDatabaseRepository;
use App\Repository\Database\Project\ProjectDatabaseRepository;
use App\Repository\Database\Role\RoleDatabaseRepository;
use App\Repository\Database\Settings\DefaultRole\DefaultRoleDatabaseRepository;
use App\Repository\Database\Settings\ProjectKey\ProjectKeyDatabaseRepository;
use App\Repository\Database\User\UserDatabaseRepository;
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
            'database' => new SimpleRepository(
                new ProjectDatabaseRepository(),
                new ListenerDatabaseRepository(),
                new HandlerMemoryRepository(),
                new AdministratorDatabaseRepository(),
                new RoleDatabaseRepository(),
                new UserDatabaseRepository(),
                new PermissionDatabaseRepository(),
                new DefaultRoleDatabaseRepository(),
                new ProjectKeyDatabaseRepository()
            )
        ];
        $repositotyType = env('REPOSITORY_TYPE', 'database');
        if (!isset($repositories[$repositotyType])) {
            throw new Exception("Repository type is not supported: " . $repositotyType);
        }

        $this->app->instance(Repository::class, $repositories[$repositotyType]);
    }

    public function boot()
    {
        
    }
}
