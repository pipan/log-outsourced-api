<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Project\ProjectEntity;
use App\Http\ResponseSchema\ProjectResponseSchemaAdapter;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class ProjectController
{
    private $projectSchema;

    public function __construct()
    {
        $this->projectSchema = new ProjectResponseSchemaAdapter();
    }

    public function index(Repository $repository)
    {
        $projects = $repository->project()->getAll();
        $adapter = AdapterHelper::listOf($this->projectSchema);
        return response()->json($adapter->adapt($projects));
    }

    public function create(Request $request, Repository $repository, HexadecimalGenerator $generator)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $project = new ProjectEntity(
            0,
            $generator->next(),
            $request->input('name')
        );
        $repository->project()->insert($project);
        return response()->json($this->projectSchema->adapt($project), 201);
    }

    public function view($uuid, Repository $repository)
    {
        $project = $repository->project()->getByUuid($uuid);

        if ($project == null) {
            return response()->json(null, 404);
        }

        return response()->json($this->projectSchema->adapt($project));
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
            return response()->json(null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'nullable', 'filled', 'max:255']
        ]);
        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $project = new ProjectEntity(
            $project->getId(),
            $project->getUuid(),
            $request->input('name', $project->getName())
        );
        $repository->project()->update($project->getId(), $project);

        return response()->json(
            $this->projectSchema->adapt($project),
            200
        );
    }

    public function generateUuid($uuid, Repository $repository, HexadecimalGenerator $generator)
    {
        $project = $repository->project()->getByUuid($uuid);
        if ($project == null) {
            return response()->json(null, 404);
        }

        $project = new ProjectEntity(
            $project->getId(),
            $generator->next(),
            $project->getName()
        );
        $repository->project()->update($project->getId(), $project);

        return response()->json(
            $this->projectSchema->adapt($project),
            200
        );
    }
}