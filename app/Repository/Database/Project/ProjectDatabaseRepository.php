<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;
use Lib\Entity\EntityBlacklistAdapter;

class ProjectDatabaseRepository implements ProjectRepository
{
    const TABLE_NAME = 'projects';

    private $readAdapter;
    private $writeAdapter;

    public function __construct()
    {
        $this->readAdapter = new ReadAdapter();
        $this->writeAdapter = new EntityBlacklistAdapter(['id']);
    }

    public function getAll()
    {
        $result = DB::table(self::TABLE_NAME)->get();

        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
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
        return DB::table(self::TABLE_NAME)
            ->where('id', '=', $project->getId())
            ->delete();
    }
}