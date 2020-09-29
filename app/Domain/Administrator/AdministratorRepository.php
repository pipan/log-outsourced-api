<?php

namespace App\Domain\Administrator;

use Lib\Pagination\PaginationEntity;

interface AdministratorRepository
{
    public function getAll(PaginationEntity $entity);
    public function countAll($search);

    public function getByUuid($uuid): ?AdministratorEntity;
    public function getByUsername($username): ?AdministratorEntity;
    public function getByInviteToken($token): ?AdministratorEntity;
    public function get($id): ?AdministratorEntity;

    public function insert(AdministratorEntity $entity): AdministratorEntity;
    public function update($id, AdministratorEntity $entity): AdministratorEntity;

    public function delete(AdministratorEntity $entity);
}