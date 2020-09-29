<?php

namespace App\Repository\File\Role;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleRepository as RoleRoleRepository;
use App\Repository\File\IndexFile;
use App\Repository\File\IndexGenerator;
use App\Repository\File\JsonFile;
use Lib\Adapter\AdapterHelper;
use Lib\Pagination\PaginationEntity;

class RoleFileRepository implements RoleRoleRepository
{
    private $jsonFile;
    private $readAdapter;
    private $writeAdapter;
    private $indexGenerator;
    private $uuidIndex;

    public function __construct()
    {
        $this->jsonFile = new JsonFile('roles.json');
        $this->readAdapter = new RoleFileReadAdapter();
        $this->writeAdapter = new RoleFileWriteAdapter();
        $this->indexGenerator = new IndexGenerator('roles');
        $this->uuidIndex = new IndexFile('roles_uuid_index');
    }

    public function exists($value)
    {
        return $this->getByUuid($value) !== null;
    }

    public function countForProject($projectId, $search)
    {
        
    }

    public function getForProject($projectId, PaginationEntity $pagination)
    {
        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt(
            $this->jsonFile->read()
        );
    }

    public function get($id): ?RoleEntity
    {
        $json = $this->jsonFile->read();
        return $this->readAdapter->adapt($json[$id] ?? null);
    }

    public function getByUuid($uuid): ?RoleEntity
    {
        $id = $this->uuidIndex->find($uuid);
        if (!$id) {
            return null;
        }
        return $this->get($id);
    }

    public function insert(RoleEntity $entity): RoleEntity
    {
        $json = $this->jsonFile->read();
        if (isset($json[$entity->getId()])) {
            return null;
        }

        $json[$entity->getId()] = $this->writeAdapter->adapt($entity);
        $this->jsonFile->write($json);
        return $entity;
    }

    public function update($id, RoleEntity $entity): RoleEntity
    {
        $json = $this->jsonFile->read();
        $json[$id] = $this->writeAdapter->adapt($entity);
        $this->jsonFile->write($json);
        return $entity;
    }

    public function delete(RoleEntity $entity)
    {
        $json = $this->jsonFile->read();
        if (!isset($json[$entity->getId()])) {
            return null;
        }
        unset($json[$entity->getId()]);
        $this->jsonFile->write($json);
    }
}