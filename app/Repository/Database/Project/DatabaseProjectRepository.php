<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;

class DatabaseProjectRepository implements ProjectRepository
{
    private $resultAdapter;

    public function __construct()
    {
        $this->resultAdapter = new ProjectDatabaseResultAdapter();
    }

    public function getAll()
    {
        $result = DB::table('projects')->get();

        $adapter = AdapterHelper::listOf($this->resultAdapter);
        return $adapter->adapt($result);
    }

    public function getByUuid($uuid)
    {
        $result = DB::table('projects')
            ->where('uuid', '=', $uuid)
            ->first();
        return $this->resultAdapter->adapt($result);
    }

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }

    public function save(ProjectEntity $project)
    {
        if ($project->getId() == 0) {
            $this->create($project);
        } else {
            $this->update($project->getId(), $project);
        }
        return $project;
    }

    public function create(ProjectEntity $project)
    {
        DB::table('projects')
            ->insert([
                'uuid' => $project->getUuid(),
                'name' => $project->getName()
            ]);
    }

    protected function update($id, ProjectEntity $project)
    {
        DB::table('projects')
            ->where('id', '=', $id)
            ->update([
                'uuid' => $project->getUuid(),
                'name' => $project->getName()
            ]);
    }

    public function delete(ProjectEntity $project)
    {
        return DB::table('projects')
            ->where('uuid', '=', $project->getUuid())
            ->delete();
    }

    public function exists($value)
    {
        return $this->getByHexUuid($value) != null;
    }
}