<?php

namespace App\Repository\Database;

use Lib\Adapter\Adapter;
use Lib\Adapter\AdapterHelper;

class AdapterDatabaseIo implements DatabaseIo
{
    private $readAdapter;
    private $writeAdapter;
    private $io;

    public function __construct(DatabaseIo $io, Adapter $readAdapter, Adapter $writeAdapter)
    {
        $this->io = $io;
        $this->readAdapter = $readAdapter;
        $this->writeAdapter = $writeAdapter;
    }

    private function adaptForReading($result)
    {
        return $this->readAdapter->adapt($result);
    }

    private function adaptForReadingList($results)
    {
        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($results);
    }

    public function select($result)
    {
        return $this->adaptForReading(
            $this->io->select($result)
        );
    }

    public function selectList($results)
    {
        return $this->adaptForReadingList(
            $this->io->selectList($results)
        );
    }

    public function findList($ids)
    {
        return $this->adaptForReadingList(
            $this->io->findList($ids)
        );
    }

    public function find($id)
    {
        return $this->adaptForReading(
            $this->io->find($id)
        );
    }

    public function insert($entity)
    {
        return $this->io->insert(
            $this->writeAdapter->adapt($entity)
        );
    }

    public function update($id, $entity)
    {
        $this->io->update(
            $id,
            $this->writeAdapter->adapt($entity)
        );
    }

    public function delete($id)
    {
        $this->io->delete($id);
    }
}