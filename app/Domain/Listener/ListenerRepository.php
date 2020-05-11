<?php

namespace App\Domain\Listener;

interface ListenerRepository
{
    public function getByUuid($uuid);
    public function getForProject($projectId);

    public function insert(ListenerEntity $listener);
    public function update($id, ListenerEntity $listener);
    
    public function delete(ListenerEntity $listener);
}