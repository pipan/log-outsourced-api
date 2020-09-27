<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectValidator;
use App\Http\ResponseError;
use App\Http\ResponseSchema\ListenerResponseSchemaAdapter;
use App\Http\ResponseSchema\ProjectResponseSchemaAdapter;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class ProjectController
{
    private $projectSchema;
    private $listenerSchema;

    public function __construct()
    {
        $this->projectSchema = new ProjectResponseSchemaAdapter();
        $this->listenerSchema = new ListenerResponseSchemaAdapter();
    }

    public function index(Repository $repository)
    {
        $projects = $repository->project()->getAll();
        $adapter = AdapterHelper::listOf($this->projectSchema);
        return response($adapter->adapt($projects));
    }

    public function create(Request $request, Repository $repository, HexadecimalGenerator $generator)
    {
        $validator = ProjectValidator::forInsert()->forRequest($request);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $project = new ProjectEntity([
            'uuid' => $generator->next(),
            'name' => $request->input('name')
        ]);

        $repository->project()->insert($project);
        return response($this->projectSchema->adapt($project), 201);
    }

    public function view($uuid, Repository $repository)
    {
        $project = $repository->project()->getByUuid($uuid);

        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $listeners = $repository->listener()->getForProject($project->getId());

        $listListenerAdapter = AdapterHelper::listOf($this->listenerSchema);
        return response([
            'project' => $this->projectSchema->adapt($project),
            'listeners' => $listListenerAdapter->adapt($listeners)
        ]);
    }

    public function delete($uuid, Repository $repository)
    {
        $project = $repository->project()->getByUuid($uuid);

        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $repository->project()->delete($project);
        return response([], 200);
    }

    public function update($uuid, Repository $repository, Request $request)
    {
        $project = $repository->project()->getByUuid($uuid);
        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $validator = ProjectValidator::forUpdate()->forRequest($request);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $project = $project->withName(
            $request->input('name', $project->getName())
        );

        $repository->project()->update($project->getId(), $project);

        return response()->json(
            $this->projectSchema->adapt($project),
            200
        );
    }
}