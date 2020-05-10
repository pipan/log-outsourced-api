<?php

namespace App\Repository\Database\Handler;

use App\Domain\Handler\HandlerEntity;
use App\Domain\Handler\HandlerRepository;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;

class DatabaseHandlerRepository implements HandlerRepository
{
    private $resultAdapter;

    public function __construct()
    {
        $this->resultAdapter = new HandlerDatabaseResultAdapter();
    }

    public function getForProject($projectId)
    {
        $result = DB::table('handlers')
            ->where('project_id', '=', $projectId)
            ->get();

        $adapter = AdapterHelper::listOf($this->resultAdapter);
        return $adapter->adapt($result);
    }

    public function save(HandlerEntity $handler)
    {
        if ($handler->getId() == 0) {
            $this->create($handler);
        } else {
            $this->update($handler->getId(), $handler);
        }
        return $handler;
    }

    protected function create(HandlerEntity $handler)
    {
        return DB::table('handlers')
            ->insert([
                'uuid' => $handler->getUuid(),
                'project_id' => $handler->getProjectId(),
                'name' => $handler->getName()
            ]);
    }

    protected function update($id, HandlerEntity $handler)
    {
        return DB::table('handlers')
            ->where('id', '=', $id)
            ->update([
                'uuid' => $handler->getUuid(),
                'project_id' => $handler->getProjectId(),
                'name' => $handler->getName()
            ]);
    }

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }

    public function getByUuid($uuid)
    {
        $result = DB::table('handlers')
            ->where('uuid', '=', $uuid)
            ->first();
        return $this->resultAdapter->adapt($result);
    }

    public function delete(HandlerEntity $handler)
    {
        return DB::table('handlers')
            ->where('uuid', '=', $handler->getUuid())
            ->delete();
    }
}