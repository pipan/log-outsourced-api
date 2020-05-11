<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\ExistsRule;
use App\Domain\Listener\ListenerEntity;
use App\Http\ResponseSchema\ListenerResponseSchemaAdapter;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Generator\HexadecimalGenerator;

class ListenerController
{
    private $schema;

    public function __construct()
    {
        $this->schema = new ListenerResponseSchemaAdapter();
    }

    public function create(Request $request, HexadecimalGenerator $generator, Repository $repository)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:255'],
            'project_uuid' => ['bail', 'required', new ExistsRule($repository->project())]
        ]);

        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $project = $repository->project()->getByUuid(
            $request->input('project_uuid')
        );

        $handler = new ListenerEntity(
            0,
            $generator,
            $project->getId(),
            $request->input('name'),
            $request->input('rules', []),
            $request->input('handler_id'),
            $request->input('handler_settings')
        );

        $repository->listener()->insert($handler);

        return response()->json(
            $this->schema->adapt($handler),
            201
        );
    }

    public function update($uuid, Request $request, Repository $repository)
    {
        $entity = $repository->listener()->getByUuid($uuid);
        if ($entity == null) {
            return response()->json(null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'nullable', 'filled', 'max:255']
        ]);
        if ($validator->fails()) {
            return response()->json(null, 422);
        }

        $entity = $repository->listener()->update(
            $entity->getId(),
            new ListenerEntity(
                $entity->getId(),
                $entity->getUuid(),
                $entity->getProjectId(),
                $request->input('name', $entity->getName()),
                $entity->getRules(),
                $entity->getHandlerId(),
                $entity->getHandlerSettings()
            )
        );
        return response()->json(
            $this->schema->adapt($entity),
            200
        );
    }

    public function delete($uuid, Repository $repository)
    {
        $entity = $repository->listener()->getByUuid($uuid);
        if ($entity == null) {
            return response()->json(null, 404);
        }

        $repository->listener()->delete($entity);
        return response()->json(null, 204);
    }
}