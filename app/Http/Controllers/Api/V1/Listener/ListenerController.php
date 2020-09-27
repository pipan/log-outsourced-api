<?php

namespace App\Http\Controllers\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerValidator;
use App\Http\ResponseSchema\ListenerResponseSchemaAdapter;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class ListenerController
{
    private $schema;
    private $errorSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->schema = new ListenerResponseSchemaAdapter();
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function index(Request $request)
    {
        $project = $this->repository->project()
            ->getByUuid($request->input('project_uuid'));
        $listeners = $this->repository->listener()
            ->getForProject(
                $project->getId(),
                Pagination::fromRequest($request)
            );

        $adapter = AdapterHelper::listOf($this->schema);
        return response($adapter->adapt($listeners));
    }

    public function create(Request $request, HexadecimalGenerator $generator)
    {
        $validator = ListenerValidator::forCreates($this->repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return response(
                $this->errorSchema->adapt($validator->errors()),
                422
            );
        }

        $project = $this->repository->project()->getByUuid(
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

        $this->repository->listener()->insert($handler);

        return response(
            $this->schema->adapt($handler),
            201
        );
    }

    public function update($uuid, Request $request)
    {
        $entity = $this->repository->listener()->getByUuid($uuid);
        if ($entity == null) {
            return response([], 404);
        }

        $validator = ListenerValidator::forUpdates($this->repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $rules = array_unique($request->input('rules', $entity->getRules()));

        $entity = $this->repository->listener()->update(
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

    public function delete($uuid)
    {
        $entity = $this->repository->listener()
            ->getByUuid($uuid);
        if ($entity === null) {
            return response([], 404);
        }

        $this->repository->listener()
            ->delete($entity);
        return response([], 200);
    }
}