<?php

namespace App\Domain\Listener;

use App\Domain\Project\ProjectAwareRepository;

interface ListenerRepository extends ProjectAwareRepository
{
    public function getAllForProject($projectId);

    public function getByUuid($uuid): ?ListenerEntity;
    public function get($id): ?ListenerEntity;

    public function insert(ListenerEntity $entity): ListenerEntity;
    public function update($id, ListenerEntity $entity): ListenerEntity;
    
    public function delete(ListenerEntity $entity);
}