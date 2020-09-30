<?php

namespace App\Repository\Database\User;

use App\Domain\User\UserEntity;
use App\Domain\User\UserRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\PaginationQuery;
use App\Repository\Database\Role\Hook\User\UserDeleteHook;
use App\Repository\Database\Role\Hook\User\UserLoadHook;
use App\Repository\Database\Role\Hook\User\UserSaveHook;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;
use Lib\Pagination\PaginationEntity;

class UserDatabaseRepository implements UserRepository
{
    const TABLE = 'users';

    private $io;

    public function __construct()
    {
        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE),
                new ReadAdapter(),
                new EntityBlacklistAdapter(['id', 'roles'])
            )
        );

        $this->io->addHook('load', new UserLoadHook());
        $this->io->addHook('save', new UserSaveHook());
        $this->io->addHook('delete', new UserDeleteHook());
    }

    public function getForProject($projectId, PaginationEntity $pagination)
    {
        $results = PaginationQuery::getExtensionForEntity($pagination)
            ->extend(DB::table(self::TABLE)->where('project_id', '=', $projectId))
            ->get();

        return $this->io->selectList($results);
    }

    public function countForProject($projectId, $search)
    {
        return PaginationQuery::getSearchExtension('username', $search)
            ->extend(DB::table(self::TABLE)->where('project_id', '=', $projectId))
            ->count();
    }

    public function getByUuid($uuid): ?UserEntity
    {
        $result = DB::table(self::TABLE)
            ->where('uuid', '=', $uuid)
            ->first();
        if (!$result) {
            return null;
        }

        return $this->io->select($result);
    }

    public function getByUsernameForProject($username, $projectId): ?UserEntity
    {
        $result = DB::table(self::TABLE)
            ->where('username', '=', $username)
            ->where('project_id', '=', $projectId)
            ->first();
        if (!$result) {
            return null;
        }

        return $this->io->select($result);
    }

    public function insert(UserEntity $entity): UserEntity
    {
        return $this->io->insert($entity);
    }

    public function update($id, UserEntity $entity): UserEntity
    {
        return $this->io->update($id, $entity);
    }

    public function delete(UserEntity $entity)
    {
        $this->io->delete($entity->getId());
    }
}