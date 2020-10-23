<?php

namespace App\Repository\Database\Settings\ProjectKey;

use App\Domain\Settings\ProjectKey\ProjectKeyEntity;
use App\Domain\Settings\ProjectKey\ProjectKeyRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\PaginationQuery;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;
use Lib\Pagination\PaginationEntity;

class ProjectKeyDatabaseRepository implements ProjectKeyRepository
{
    const TABLE = 'project_keys';

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

    public function getByKey($key): ?ProjectKeyEntity
    {
        $result = DB::table(self::TABLE)
            ->where('key', '=', $key)
            ->first();
        if ($result === null) {
            return null;
        }

        return $this->io->select($result);
    }

    public function insert(ProjectKeyEntity $entity): ProjectKeyEntity
    {
        return $this->io->insert($entity);
    }

    public function update($id, ProjectKeyEntity $entity): ProjectKeyEntity
    {
        return $this->io->update($id, $entity);
    }

    public function delete(ProjectKeyEntity $entity)
    {
        $this->io->delete($entity->getId());
    }
}