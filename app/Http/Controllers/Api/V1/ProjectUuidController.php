<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ResponseSchema\ProjectResponseSchemaAdapter;
use App\Repository\Repository;
use Lib\Generator\HexadecimalGenerator;

class ProjectUuidController
{
    private $projectSchema;

    public function __construct()
    {
        $this->projectSchema = new ProjectResponseSchemaAdapter();
    }

    public function generate($uuid, Repository $repository, HexadecimalGenerator $generator)
    {
        $project = $repository->project()->getByUuid($uuid);
        if ($project == null) {
            return response([], 404);
        }

        $project = $project->withUuid($generator->next());
        $repository->project()->update($project->getId(), $project);

        return response(
            $this->projectSchema->adapt($project),
            200
        );
    }
}