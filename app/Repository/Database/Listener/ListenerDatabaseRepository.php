<?php

namespace App\Repository\Database\Listener;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerRepository;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;

class ListenerDatabaseRepository implements ListenerRepository
{
    const TABLE_NAME = 'listeners';

    private $readAdapter;
    private $writeAdapter;

    public function __construct()
    {
        $this->readAdapter = new ListenerDatabaseReadAdapter();
        $this->writeAdapter = new ListenerDatabaseWriteAdapter();
    }

    public function getForProject($projectId)
    {
        $result = DB::table(self::TABLE_NAME)
            ->where('project_id', '=', $projectId)
            ->get();

        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
    }

    public function insert(ListenerEntity $listener)
    {
        DB::table(self::TABLE_NAME)
            ->insert($this->writeAdapter->adapt($listener));
        return $listener;
    }

    public function update($id, ListenerEntity $listener)
    {
        DB::table(self::TABLE_NAME)
            ->where('id', '=', $id)
            ->update($this->writeAdapter->adapt($listener));
        return $listener;
    }

    public function getByUuid($uuid)
    {
        $result = DB::table(self::TABLE_NAME)
            ->where('uuid', '=', $uuid)
            ->first();
        return $this->readAdapter->adapt($result);
    }

    public function delete(ListenerEntity $listener)
    {
        return DB::table(self::TABLE_NAME)
            ->where('id', '=', $listener->getId())
            ->delete();
    }
}