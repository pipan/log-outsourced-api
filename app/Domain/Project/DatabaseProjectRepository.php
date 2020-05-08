<?php

namespace App\Domain\Project;

use Illuminate\Support\Facades\DB;

class DatabaseProjectRepository implements ProjectRepository
{
    public function getAll()
    {
        $result = DB::table('projects')->get();
        $projects = [];
        foreach ($result as $item) {
            $projects[] = new ProjectEntity($item->uid, $item->name);
        }
        return $projects;
    }

    public function getByUuid($uuid)
    {
        $result = DB::table('projects')
            ->where('uuid', '=', $uuid)
            ->first();
        return new ProjectEntity($result->uuid, $result->name);
    }

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }

    public function create(ProjectEntity $project)
    {
        DB::table('projects')
            ->insert([
                'uuid' => $project->getUuid(),
                'name' => $project->getName()
            ]);
    }

    public function deleteByUuid($uuid)
    {
        return DB::table('projects')
            ->where('uuid', '=', $uuid)
            ->delete();
    }

    public function deleteByHexUuid($hexUuid)
    {
        return $this->deleteByUuid(hex2bin($hexUuid));
    }
}