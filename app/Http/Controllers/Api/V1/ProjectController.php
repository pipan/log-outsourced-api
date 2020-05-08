<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Project\ProjectAdapter;
use App\Domain\Project\ProjectEntity;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\UidGenerator;

class ProjectController
{
    private $projectAdapter;

    public function __construct()
    {
        $this->projectAdapter = new ProjectAdapter();
    }

    public function index(Repository $repository)
    {
        $projects = $repository->project()->getAll();
        $adapter = AdapterHelper::listOf($this->projectAdapter);
        return response()->json($adapter->adapt($projects));
    }

    public function create(Request $request, Repository $repository, UidGenerator $generator)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $project = new ProjectEntity($generator->next(), $request->input('name'));
        $repository->project()->create($project);
        return response()->json($this->projectAdapter->adapt($project), 201);
    }

    public function view($hexUuid, Repository $repository)
    {
        $project = $repository->project()->getByHexUuid($hexUuid);

        if ($project == null) {
            return response()->json(null, 404);
        }

        return response()->json($this->projectAdapter->adapt($project));
    }

    public function delete($hexUuid, Repository $repository)
    {
        $project = $repository->project()->getByHexUuid($hexUuid);

        if ($project == null) {
            return response()->json(null, 404);
        }

        $repository->project()->deleteByHexUuid($hexUuid);

        return response()->json(null, 204);
    }
}