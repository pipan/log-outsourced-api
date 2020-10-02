<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\PaginationQuery;
use App\Repository\Database\Project\Hook\ProjectAwareDeleteHook;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;
use Lib\Entity\EntityBlacklistAdapter;
use Lib\Pagination\PaginationEntity;

class ProjectDatabaseRepository implements ProjectRepository
{
    const TABLE_NAME = 'projects';

    private $io;
    private $readAdapter;
    private $writeAdapter;

    public function __construct()
    {
        $this->readAdapter = new ReadAdapter();
        $this->writeAdapter = new EntityBlacklistAdapter(['id']);

        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE_NAME),
                $this->readAdapter,
                $this->writeAdapter
            )
        );

        $this->io->addHook('delete', new ProjectAwareDeleteHook('listeners'));
        $this->io->addHook('delete', new ProjectAwareDeleteHook('roles'));
        $this->io->addHook('delete', new ProjectAwareDeleteHook('users'));
        $this->io->addHook('delete', new ProjectAwareDeleteHook('permissions'));
    }

    public function getAll(PaginationEntity $pagination)
    {
        $result = PaginationQuery::getExtensionForEntity($pagination)
            ->extend(DB::table(self::TABLE_NAME))
            ->get();

        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
    }

    public function count($search)
    {
        $result = PaginationQuery::getSearchExtension('name', $search)
            ->extend(DB::table(self::TABLE_NAME))
            ->count();

        return $result;
    }

    public function getByUuid($uuid): ?ProjectEntity
    {
        $result = DB::table(self::TABLE_NAME)
            ->where('uuid', '=', $uuid)
            ->first();
        return $this->readAdapter->adapt($result);
    }

    public function insert(ProjectEntity $project): ProjectEntity
    {
        DB::table(self::TABLE_NAME)
            ->insert($this->writeAdapter->adapt($project));
        return $project;
    }

    public function update($id, ProjectEntity $project): ProjectEntity
    {
        DB::table(self::TABLE_NAME)
            ->where('id', '=', $id)
            ->update($this->writeAdapter->adapt($project));
        return $project;
    }

    public function delete(ProjectEntity $project)
    {
        $this->io->delete($project->getId());
    }
}