<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\ExistsRule;
use App\Domain\Handler\HandlerAdapter;
use App\Domain\Handler\HandlerEntity;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Generator\UidGenerator;

class HandlerController
{
    private $handlerAdapter;

    public function __construct()
    {
        $this->handlerAdapter = new HandlerAdapter();
    }

    public function create(Request $request, UidGenerator $uidGenerator, Repository $repository)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:255'],
            'project_uuid' => ['bail', 'required', new ExistsRule($repository->project())]
        ]);

        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $project = $repository->project()->getByHexUuid(
            $request->input('project_uuid')
        );

        $handler = new HandlerEntity(
            0,
            $uidGenerator,
            $project->getId(),
            $request->input('name')
        );

        $repository->handler()->save($handler);

        return response()->json(
            $this->handlerAdapter->adapt($handler),
            201
        );
    }

    public function update($hexUuid, Request $request, Repository $repository)
    {
        $handler = $repository->handler()->getByHexUuid($hexUuid);
        if ($handler == null) {
            return response()->json(null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'nullable', 'filled', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $handler = $repository->handler()->save(
            new HandlerEntity(
                $handler->getId(),
                $handler->getUuid(),
                $handler->getProjectId(),
                $request->input('name', $handler->getName())
            )
        );
        return response()->json(
            $this->handlerAdapter->adapt($handler),
            200
        );
    }

    public function delete($hexUuid, Repository $repository)
    {
        $handler = $repository->handler()->getByHexUuid($hexUuid);
        if ($handler == null) {
            return response()->json(null, 404);
        }

        $repository->handler()->delete($handler);
        return response()->json(null, 204);
    }
}