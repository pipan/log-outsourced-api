<?php

namespace App\Domain\User;

use App\Domain\Project\ProjectAwareRepository;

interface UserRepository extends ProjectAwareRepository
{
    public function getByUuid($uuid): ?UserEntity;
    public function getByUsernameForProject($username, $projectId): ?UserEntity;

    public function insert(UserEntity $entity): UserEntity;
    public function update($id, UserEntity $entity): UserEntity;

    public function delete(UserEntity $entity);
}