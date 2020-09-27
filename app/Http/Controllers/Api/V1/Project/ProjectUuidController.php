<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\ResponseError;
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
        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $project = $project->withUuid($generator->next());
        $repository->project()->update($project->getId(), $project);

        return response(
            $this->projectSchema->adapt($project),
            200
        );
    }
}