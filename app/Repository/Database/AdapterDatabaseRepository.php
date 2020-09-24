<?php

namespace App\Repository\Database;

use Lib\Adapter\Adapter;
use Lib\Adapter\AdapterHelper;

class AdapterDatabaseRepository
{
    private $readAdapter;
    private $writeAdapter;
    private $simpleRepository;

    public function __construct($table, Adapter $readAdapter, Adapter $writeAdapter)
    {
        $this->simpleRepository = new SimpleDatabaseRepository($table);
        $this->readAdapter = $readAdapter;
        $this->writeAdapter = $writeAdapter;
    }

    public function getAll()
    {
        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt(
            $this->simpleRepository->getAll()
        );
    }

    public function get($id)
    {
        return $this->readAdapter->adapt(
            $this->simpleRepository->get($id)
        );
    }

    public function insert($entity)
    {
        return $this->simpleRepository->insert(
            $this->writeAdapter->adapt($entity)
        );
    }

    public function update($id, $entity)
    {
        $this->simpleRepository->update(
            $id,
            $this->writeAdapter->adapt($entity)
        );
    }

    public function delete($id)
    {
        $this->simpleRepository->delete($id);
    }
}