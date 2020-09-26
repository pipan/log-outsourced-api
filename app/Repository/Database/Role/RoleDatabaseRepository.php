<?php

namespace App\Repository\Database\Role;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\Permission\Hook\Role\RoleDeleteHook;
use App\Repository\Database\Permission\Hook\Role\RoleLoadHook;
use App\Repository\Database\Permission\Hook\Role\RoleSaveHook;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;

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

    public function getForProject($projectId, $config = [])
    {
        $results = DB::table(self::TABLE)
            ->where('project_id', '=', $projectId)
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