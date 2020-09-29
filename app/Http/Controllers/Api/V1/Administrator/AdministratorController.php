<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorSchema;
use App\Http\ResponseError;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;

class AdministratorController
{
    private $repository;
    private $adminPublicSchema;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->adminPublicSchema = AdministratorSchema::forPublic();
    }

    public function index(Request $request)
    {
        $administrators = $this->repository->administrator()
            ->getAll(Pagination::fromRequest($request));

        $adapter = AdapterHelper::listOf($this->adminPublicSchema);
        return response($adapter->adapt($administrators));
    }

    public function delete($uuid)
    {
        $administrator = $this->repository->administrator()
            ->getByUuid($uuid);

        if (!$administrator) {
            return ResponseError::resourceNotFound();
        }

        $this->repository->administrator()
            ->delete($administrator);

        return response([]);
    }
}