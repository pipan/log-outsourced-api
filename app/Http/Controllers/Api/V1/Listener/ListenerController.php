<?php

namespace App\Http\Controllers\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerValidator;
use App\Http\ResponseError;
use App\Http\ResponseSchema\ListenerResponseSchemaAdapter;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class ListenerController
{
    private $schema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->schema = new ListenerResponseSchemaAdapter();
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
            return ResponseError::invalidRequest($validator->errors());
        }

        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid')
        );

        $rules = array_unique($request->input('rules', []));

        $handler = new ListenerEntity([
            'uuid' => $generator->next(),
            'project_id' => $project->getId(),
            'name' => $request->input('name'),
            'rules' => $rules,
            'handler_slug' => $request->input('handler_slug'),
            'handler_values' => $request->input('handler_values', [])
        ]);

        $this->repository->listener()->insert($handler);

        return response($this->schema->adapt($handler), 201);
    }

    public function update($uuid, Request $request)
    {
        $entity = $this->repository->listener()->getByUuid($uuid);
        if (!$entity) {
            return ResponseError::resourceNotFound();
        }

        $validator = ListenerValidator::forUpdates($this->repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $rules = array_unique($request->input('rules', $entity->getRules()));

        $entity = $entity->withName($request->input('name', $entity->getName()))
            ->withRules($rules)
            ->withHandlerSlug($request->input('handler_slug', $entity->getHandlerSlug()))
            ->withHandlerValues($request->input('handler_values', $entity->getHandlerValues()));

        $entity = $this->repository->listener()
            ->update($entity->getId(), $entity);

        return response()->json($this->schema->adapt($entity));
    }

    public function delete($uuid)
    {
        $entity = $this->repository->listener()
            ->getByUuid($uuid);
        if (!$entity) {
            return ResponseError::resourceNotFound();
        }

        $this->repository->listener()
            ->delete($entity);
        return response([]);
    }
}