<?php

namespace App\Domain\Listener;

interface ListenerRepository
{
    public function getByUuid($uuid): ?ListenerEntity;
    public function getForProject($projectId);

    public function insert(ListenerEntity $entity): ListenerEntity;
    public function update($id, ListenerEntity $entity): ListenerEntity;
    
    public function delete(ListenerEntity $entity);
}