<?php

namespace App\Repository\File\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorRepository;
use App\Repository\File\Administrator\AdministratorFileReadAdapter;
use App\Repository\File\Administrator\AdministratorFileWriteAdapter;
use App\Repository\File\IndexFile;
use App\Repository\File\IndexGenerator;
use App\Repository\File\JsonFile;

class AdministratorFileRepository implements AdministratorRepository
{
    private $readAdapter;
    private $writeAdapter;
    private $jsonFile;
    private $indexGenerator;

    private $usernameIndex;

    public function __construct()
    {
        $this->readAdapter = new AdministratorFileReadAdapter();
        $this->writeAdapter = new AdministratorFileWriteAdapter();
        $this->jsonFile = new JsonFile('administrators.json');
        $this->indexGenerator = new IndexGenerator('administratos');
        $this->usernameIndex = new IndexFile('administrators_username_index');
    }

    public function exists($value)
    {
        return $this->getByUsername($value) !== null;
    }

    public function get($id): ?AdministratorEntity
    {
        $json = $this->jsonFile->read();
        if (!isset($json[$id])) {
            return null;
        }
        return $this->readAdapter->adapt($json[$id]);
    }

    public function getByUsername($username): ?AdministratorEntity
    {
        $id = $this->usernameIndex->find($username);
        if (!$id) {
            return null;
        }
        return $this->get($id);
    }

    public function insert(AdministratorEntity $entity): AdministratorEntity
    {
        $json = $this->jsonFile->read();
        $entity = new AdministratorEntity(
            $this->indexGenerator->next(),
            $entity->getUsername(),
            $entity->getPasswordHash(),
            $entity->getInviteToken()
        );
        $json[$entity->getId()] = $this->writeAdapter->adapt($entity);
        $this->jsonFile->write($json);
        $this->usernameIndex->set($entity->getUsername(), $entity->getId());
        return $entity;
    }

    public function update($id, AdministratorEntity $entity): AdministratorEntity
    {
        $this->delete($this->get($id));
        return $this->insert($entity);
    }

    public function delete(AdministratorEntity $entity)
    {
        $json = $this->jsonFile->read();
        unset($json[$entity->getId()]);
        $this->usernameIndex->delete($entity->getUsername());
        $this->jsonFile->write($json);
    }
}