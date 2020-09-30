<?php

namespace App\Repository\Database\Permission;

use App\Domain\Permission\PermissionEntity;
use App\Domain\Permission\PermissionRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;

class PermissionDatabaseRepository implements PermissionRepository
{
    const TABLE = 'permissions';

    private $io;

    public function __construct()
    {
        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE),
                new ReadAdapter(),
                new EntityBlacklistAdapter(['id'])
            )
        );
    }

    public function getAllForProject($projectId)
    {
        $results = DB::table(self::TABLE)
            ->where('project_id', '=', $projectId)
            ->get();

        return $this->io->selectList($results);
    }

    public function getByNameForProject($name, $projectId)
    {
        $result = DB::table(self::TABLE)
            ->where('project_id', '=', $projectId)
            ->where('name', '=', $name)
            ->first();
        if (!$result) {
            return null;
        }

        return $this->io->select($result);
    }

    public function insert(PermissionEntity $entity): ?PermissionEntity
    {
        return $this->io->insert($entity);
    }
}