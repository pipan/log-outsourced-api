<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Project\ProjectEntity;
use App\Http\ResponseSchema\ListenerResponseSchemaAdapter;
use App\Http\ResponseSchema\ProjectResponseSchemaAdapter;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class ProjectController
{
    private $projectSchema;
    private $listenerSchema;
    private $errorSchema;

    public function __construct()
    {
        $this->projectSchema = new ProjectResponseSchemaAdapter();
        $this->listenerSchema = new ListenerResponseSchemaAdapter();
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function index(Repository $repository)
    {
        $projects = $repository->project()->getAll();
        $adapter = AdapterHelper::listOf($this->projectSchema);
        return response($adapter->adapt($projects));
    }

    public function create(Request $request, Repository $repository, HexadecimalGenerator $generator)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:255']
        ]);

        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
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

        if ($project == null) {
            return response([], 404);
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

        if ($project == null) {
            return response([], 404);
        }

        $repository->project()->delete($project);
        return response([], 200);
    }

    public function update($uuid, Repository $repository, Request $request)
    {
        $project = $repository->project()->getByUuid($uuid);

        if ($project == null) {
            return response([], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'nullable', 'filled', 'max:255']
        ]);
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
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