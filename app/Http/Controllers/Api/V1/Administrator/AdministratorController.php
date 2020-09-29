<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorSchema;
use App\Http\Controllers\Api\V1\ListMetaEntity;
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
        $pagination = Pagination::fromRequest($request)
            ->searchBy('username')
            ->orderBy('username');
        $administrators = $this->repository->administrator()
            ->getAll($pagination);

        $count = $this->repository->administrator()
            ->countAll($pagination->getSearchValue());

        $listMeta = ListMetaEntity::fromPagination($pagination)
            ->withTotalItems($count);
        $adapter = AdapterHelper::listOf($this->adminPublicSchema);
        return response([
            'items' => $adapter->adapt($administrators),
            'meta' => $listMeta->toArray()
        ]);
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