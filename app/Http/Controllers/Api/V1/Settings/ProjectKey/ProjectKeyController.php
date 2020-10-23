<?php

namespace App\Http\Controllers\Api\V1\Settings\ProjectKey;

use App\Domain\Settings\ProjectKey\ProjectKeyEntity;
use App\Domain\Settings\ProjectKey\ProjectKeyValidator;
use App\Http\Controllers\Api\V1\ListMetaEntity;
use App\Http\ResponseError;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Entity\EntityWhitelistAdapter;
use Lib\Generator\HexadecimalGenerator;

class ProjectKeyController
{
    private $roleSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->roleSchema = new EntityWhitelistAdapter(['key', 'name']);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid', '')
        );
        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $pagination = Pagination::fromRequest($request)
            ->searchBy('name')
            ->orderBy('name');
        $roles = $this->repository->projectKey()
            ->getForProject($project->getId(), $pagination);
        $count = $this->repository->projectKey()
            ->countForProject($project->getId(), $pagination->getSearchValue());

        $listMeta = ListMetaEntity::fromPagination($pagination)
            ->withTotalItems($count);
        $adapter = AdapterHelper::listOf($this->roleSchema);
        return response([
            'items' => $adapter->adapt($roles),
            'meta' => $listMeta->toArray()
        ]);
    }

    public function create(Request $request, HexadecimalGenerator $generator)
    {
        $validator = ProjectKeyValidator::forCreate($this->repository)->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }
        $project = $this->repository->project()
            ->getByUuid($request->input('project_uuid'));

        $key = new ProjectKeyEntity([
            'key' => $generator->next() . $generator->next(),
            'project_id' => $project->getId(),
            'name' => $request->input('name')
        ]);
        $key = $this->repository->projectKey()
            ->insert($key);
        return response($this->roleSchema->adapt($key), 201);
    }

    public function delete($key)
    {
        $key = $this->repository->projectKey()->getByKey($key);

        if (!$key) {
            return ResponseError::resourceNotFound();
        }

        $this->repository->projectKey()
            ->delete($key);
        return response([]);
    }

    public function update($key, Request $request)
    {
        $projectKey = $this->repository->projectKey()->getByKey($key);
        if (!$projectKey) {
            return ResponseError::resourceNotFound();
        }

        $validator = ProjectKeyValidator::forUpdate()->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $projectKey = $projectKey->withName($request->input('name', $projectKey->getName()));

        $projectKey = $this->repository->projectKey()
            ->update($projectKey->getId(), $projectKey);
        return response($this->roleSchema->adapt($projectKey), 200);
    }
}