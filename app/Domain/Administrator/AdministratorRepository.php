<?php

namespace App\Domain\Administrator;

use App\Domain\ExistsValidable;

interface AdministratorRepository extends ExistsValidable
{
    public function getByUsername($username): ?AdministratorEntity;
    public function get($id): ?AdministratorEntity;

    public function insert(AdministratorEntity $entity): AdministratorEntity;
    public function update($id, AdministratorEntity $entity): AdministratorEntity;

    public function delete(AdministratorEntity $entity);
}