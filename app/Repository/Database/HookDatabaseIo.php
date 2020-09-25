<?php

namespace App\Repository\Database;

use Lib\Hook\ActionHook;
use Lib\Hook\HookChain;

class HookDatabaseIo implements DatabaseIo
{
    private $io;
    private $hooks;

    public function __construct(DatabaseIo $io)
    {
        $this->io = $io;
        $this->hooks = [];
    }

    public function select($result) {
        $entity = $this->io->select($result);
        $entities = $this->trigger('load', [$entity]);
        return $entities[0] ?? null;
    }

    public function selectList($results) {
        $entities = $this->io->selectList($results);
        return $this->trigger('load', $entities);
    }

    public function findList($ids) {
        $entities = $this->io->findList($ids);
        return $this->trigger('load', $entities);
    }

    public function find($id)
    {
        $entity = $this->io->find($id);
        $entities = $this->trigger('load', [$entity]);
        return $entities[0] ?? null;
    }

    public function insert($entity)
    {
        $id = $this->io->insert($entity);
        $this->hooks['save']->onAction($entity);
        return $this->find($id);
    }

    public function update($id, $entity)
    {
        $this->io->update($id, $entity);
        $this->hooks['save']->onAction($entity);
        return $this->find($id);
    }

    public function delete($id)
    {
        $this->hooks['delete']->onAction(
            $this->find($id)
        );
        $this->io->delete($id);
    }

    public function addHook($name, ActionHook $hook)
    {
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = new HookChain();
        }
        $this->hooks[$name]->add($hook);
    }

    public function trigger($name, $data)
    {
        if (!isset($this->hooks[$name])) {
            return;
        }
        return $this->hooks[$name]->onAction($data);
    }
}