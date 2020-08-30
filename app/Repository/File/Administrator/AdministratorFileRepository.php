<?php

namespace App\Repository\File\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorRepository;
use App\Repository\File\Administrator\AdministratorFileReadAdapter;
use App\Repository\File\Administrator\AdministratorFileWriteAdapter;
use App\Repository\File\IndexGenerator;
use App\Repository\File\JsonFile;
use Lib\Adapter\AdapterHelper;

class AdministratorFileRepository implements AdministratorRepository
{
    private $readAdapter;
    private $writeAdapter;
    private $jsonFile;
    private $indexGenerator;

    public function __construct()
    {
        $this->readAdapter = new AdministratorFileReadAdapter();
        $this->writeAdapter = new AdministratorFileWriteAdapter();
        $this->jsonFile = new JsonFile('administrators.json');
        $this->indexGenerator = new IndexGenerator('administratos');
    }

    public function getByUsername($username): ?AdministratorEntity
    {
        $json = $this->jsonFile->read();
        if (isset($json[$username])) {
            return $this->readAdapter->adapt($json[$username]);
        }
        return null;
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
        $json[$entity->getUsername()] = $this->writeAdapter->adapt($entity);
        $this->jsonFile->write($json);
        return $entity;
    }

    public function update($username, AdministratorEntity $entity): AdministratorEntity
    {
        $this->delete($this->getByUsername($username));
        return $this->insert($entity);
    }

    public function delete(AdministratorEntity $entity)
    {
        $json = $this->jsonFile->read();
        unset($json[$entity->getUsername()]);
        $this->jsonFile->write($json);
    }
}