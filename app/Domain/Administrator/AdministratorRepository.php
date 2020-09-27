<?php

namespace App\Domain\Administrator;

interface AdministratorRepository
{
    public function getAll($config = []);

    public function getByUuid($uuid): ?AdministratorEntity;
    public function getByUsername($username): ?AdministratorEntity;
    public function getByInviteToken($token): ?AdministratorEntity;
    public function get($id): ?AdministratorEntity;

    public function insert(AdministratorEntity $entity): AdministratorEntity;
    public function update($id, AdministratorEntity $entity): AdministratorEntity;

    public function delete(AdministratorEntity $entity);
}