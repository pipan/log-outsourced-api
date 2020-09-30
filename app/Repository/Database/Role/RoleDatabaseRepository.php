<?php

namespace App\Repository\Database\Role;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\PaginationQuery;
use App\Repository\Database\Permission\Hook\Role\RoleDeleteHook;
use App\Repository\Database\Permission\Hook\Role\RoleLoadHook;
use App\Repository\Database\Permission\Hook\Role\RoleSaveHook;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;
use Lib\Pagination\PaginationEntity;

class RoleDatabaseRepository implements RoleRepository
{
    const TABLE = 'roles';

    private $io;

    public function __construct()
    {
        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE),
                new ReadAdapter(),
                new EntityBlacklistAdapter(['id', 'permissions'])
            )
        );

        $this->io->addHook('load', new RoleLoadHook());
        $this->io->addHook('save', new RoleSaveHook());
        $this->io->addHook('delete', new RoleDeleteHook());
    }

    public function countForProject($projectId, $search)
    {
        return PaginationQuery::getSearchExtension('name', $search)
            ->extend(DB::table(self::TABLE)->where('project_id', '=', $projectId))
            ->count();
    }

    public function getForProject($projectId, PaginationEntity $pagination)
    {
        $results = PaginationQuery::getExtensionForEntity($pagination)
            ->extend(DB::table(self::TABLE)->where('project_id', '=', $projectId))
            ->get();
        
        return $this->io->selectList($results);
    }

    public function getForUser($userId)
    {
        $results = DB::table('roles_users')
            ->select(['roles.*'])
            ->join(self::TABLE, 'roles_users.role_id', '=', 'roles.id')
            ->where('roles_users.user_id', '=', $userId)
            ->get();

        return $this->io->selectList($results);
    }

    public function getByUuid($uuid): ?RoleEntity
    {
        $result = DB::table(self::TABLE)
            ->where('uuid', '=', $uuid)
            ->first();
        if ($result === null) {
            return null;
        }

        return $this->io->select($result);
    }

    public function insert(RoleEntity $entity): RoleEntity
    {
        return $this->io->insert($entity);
    }

    public function update($id, RoleEntity $entity): RoleEntity
    {
        return $this->io->update($id, $entity);
    }

    public function delete(RoleEntity $entity)
    {
        $this->io->delete($entity->getId());
    }
}