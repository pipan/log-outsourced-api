<?php

namespace App\Domain\Listener;

interface ListenerRepository
{
    public function getByUuid($uuid): ?ListenerEntity;
    public function getForProject($projectId);

    public function insert(ListenerEntity $listener): ListenerEntity;
    public function update($id, ListenerEntity $listener): ListenerEntity;
    
    public function delete(ListenerEntity $listener);
}