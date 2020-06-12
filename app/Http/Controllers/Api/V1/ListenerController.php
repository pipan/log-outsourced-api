<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\ExistsRule;
use App\Domain\Listener\ListenerEntity;
use App\Http\ResponseSchema\ListenerResponseSchemaAdapter;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Generator\HexadecimalGenerator;

class ListenerController
{
    private $schema;
    private $errorSchema;

    public function __construct()
    {
        $this->schema = new ListenerResponseSchemaAdapter();
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function create(Request $request, HexadecimalGenerator $generator, Repository $repository)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:255'],
            'project_uuid' => ['bail', 'required', new ExistsRule($repository->project())],
            'rules' => ['nullable', 'array'],
            'handler_slug' => ['required', new ExistsRule($repository->handler())]
        ]);

        if ($validator->fails()) {
            return response(
                $this->errorSchema->adapt($validator->errors()),
                422
            );
        }

        $project = $repository->project()->getByUuid(
            $request->input('project_uuid')
        );

        $rules = array_unique($request->input('rules', []));

        $handler = new ListenerEntity(
            0,
            $generator->next(),
            $project->getId(),
            $request->input('name'),
            $rules,
            $request->input('handler_slug'),
            encrypt($request->input('handler_values', []))
        );

        $repository->listener()->insert($handler);

        return response(
            $this->schema->adapt($handler),
            201
        );
    }

    public function update($uuid, Request $request, Repository $repository)
    {
        $entity = $repository->listener()->getByUuid($uuid);
        if ($entity == null) {
            return response([], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'nullable', 'filled', 'max:255'],
            'rules' => ['bail', 'nullable', 'array'],
            'handler_slug' => ['bail', 'nullable', new ExistsRule($repository->handler())]
        ]);
        if ($validator->fails()) {
            return response([], 422);
        }

        $rules = array_unique($request->input('rules', $entity->getRules()));

        $entity = $repository->listener()->update(
            $entity->getId(),
            new ListenerEntity(
                $entity->getId(),
                $entity->getUuid(),
                $entity->getProjectId(),
                $request->input('name', $entity->getName()),
                $rules,
                $request->input('handler_slug', $entity->getHandlerSlug()),
                encrypt($request->input('handler_values', decrypt($entity->getHandlerValues())))
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
            return response([], 404);
        }

        $repository->listener()->delete($entity);
        return response([], 200);
    }
}