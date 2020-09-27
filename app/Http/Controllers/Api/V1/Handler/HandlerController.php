<?php

namespace App\Http\Controllers\Api\V1\Handler;

use App\Http\ResponseSchema\HandlerResponseSchemaAdapter;
use App\Repository\Repository;
use Lib\Adapter\AdapterHelper;

class HandlerController
{
    private $schema;

    public function __construct()
    {
        $this->schema = new HandlerResponseSchemaAdapter();
    }

    public function index(Repository $repository)
    {
        $entities = $repository->handler()->getAll();

        $adapter = AdapterHelper::listOf($this->schema);
        return response()->json(
            $adapter->adapt($entities),
            200
        );
    }

    public function view($slug, Repository $repository)
    {
        $entity = $repository->handler()->getBySlug($slug);

        if ($entity == null) {
            return response()->json(null, 404);
        }

        return response()->json(
            $this->schema->adapt($entity),
            200
        );
    }
}