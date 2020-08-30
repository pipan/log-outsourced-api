<?php

namespace App\Domain\Administrator;

interface AdministratorRepository
{
    public function getByUsername($username): ?AdministratorEntity;

    public function insert(AdministratorEntity $entity): AdministratorEntity;
    public function update($id, AdministratorEntity $entity): AdministratorEntity;

    public function delete(AdministratorEntity $entity);
}