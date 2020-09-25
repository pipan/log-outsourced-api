<?php

namespace App\Repository\File\Listener;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerRepository;
use App\Repository\File\IndexGenerator;
use App\Repository\File\JsonFile;
use Lib\Adapter\AdapterHelper;

class ListenerFileRepository implements ListenerRepository
{
    private $indexGenerator;
    private $jsonFile;
    private $readAdapter;
    private $writeAdapter;

    public function __construct()
    {
        $this->jsonFile = new JsonFile('listeners.json');
        $this->indexGenerator = new IndexGenerator('listeners');
        $this->readAdapter = new ListenerFileReadAdapter();
        $this->writeAdapter = new ListenerFileWriteAdapter();
    }

    private function getAll()
    {
        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($this->jsonFile->read());
    }

    public function get($id): ?ListenerEntity
    {
        return null;
    }

    public function getForProject($projectId, $config = [])
    {
        $all = $this->getAll();
        $result = [];
        foreach ($all as $listener) {
            if ($listener->getProjectId() != $projectId) {
                continue;
            }
            $result[] = $listener;
        }
        return $result;
    }

    public function getByUuid($uuid): ?ListenerEntity
    {
        $all = $this->getAll();
        foreach ($all as $listener) {
            if ($listener->getUuid() == $uuid) {
                return $listener;
            }
        }
        return null;
    }

    public function insert(ListenerEntity $entity): ListenerEntity
    {
        $all = $this->getAll();
        $listener = new ListenerEntity(
            $this->indexGenerator->next(),
            $entity->getUuid(),
            $entity->getProjectId(),
            $entity->getName(),
            $entity->getRules(),
            $entity->getHandlerSlug(),
            $entity->getHandlerValues()
        );

        $all[] = $listener;

        $this->save($all);
        return $listener;
    }

    public function update($id, ListenerEntity $entity): ListenerEntity
    {
        $all = $this->getAll();
        foreach ($all as $key => $listener) {
            if ($listener->getId() == $entity->getId()) {
                $all[$key] = $entity;
            }
        }
        $this->save($all);
        return $entity;
    }

    public function delete(ListenerEntity $entity)
    {
        $all = $this->getAll();
        foreach ($all as $key => $listener) {
            if ($listener->getId() == $entity->getId()) {
                unset($all[$key]);
            }
        }
        $this->save($all);
    }

    private function save($all)
    {
        $adapter = AdapterHelper::listOf($this->writeAdapter);
        $this->jsonFile->write(
            $adapter->adapt($all)
        );
    }
}