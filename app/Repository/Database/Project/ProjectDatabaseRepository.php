<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;

class ProjectDatabaseRepository implements ProjectRepository
{
    const TABLE_NAME = 'projects';

    private $readAdapter;
    private $writeAdapter;

    public function __construct()
    {
        $this->readAdapter = new ProjectDatabaseReadAdapter();
        $this->writeAdapter = new ProjectDatabaseWriteAdapter();
    }

    public function getAll()
    {
        $result = DB::table(self::TABLE_NAME)->get();

        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
    }

    public function getByUuid($uuid)
    {
        $result = DB::table(self::TABLE_NAME)
            ->where('uuid', '=', $uuid)
            ->first();
        return $this->readAdapter->adapt($result);
    }

    public function insert(ProjectEntity $project)
    {
        DB::table(self::TABLE_NAME)
            ->insert($this->writeAdapter->adapt($project));
        return $project;
    }

    public function update($id, ProjectEntity $project)
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

    public function exists($value)
    {
        return $this->getByUuid($value) != null;
    }
}