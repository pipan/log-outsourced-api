<?php

namespace App\Domain\Settings\ProjectKey;

use App\Domain\Project\ProjectAwareRepository;

interface ProjectKeyRepository extends ProjectAwareRepository
{
    public function getByKey($key): ?ProjectKeyEntity;

    public function insert(ProjectKeyEntity $entity): ProjectKeyEntity;
    public function update($id, ProjectKeyEntity $entity): ProjectKeyEntity;

    public function delete(ProjectKeyEntity $entity);
}