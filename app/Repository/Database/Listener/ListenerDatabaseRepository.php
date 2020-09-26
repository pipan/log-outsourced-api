<?php

namespace App\Repository\Database\Listener;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\Rule\Hook\Listener\ListenerDeleteHook;
use App\Repository\Database\Rule\Hook\Listener\ListenerLoadHook;
use App\Repository\Database\Rule\Hook\Listener\ListenerSaveHook;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;

class ListenerDatabaseRepository implements ListenerRepository
{
    const TABLE = 'listeners';

    private $io;

    public function __construct()
    {
        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE),
                new ReadAdapter(),
                new WriteAdapter()
            )
        );

        $this->io->addHook('save', new ListenerSaveHook());
        $this->io->addHook('delete', new ListenerDeleteHook());
        $this->io->addHook('load', new ListenerLoadHook());
    }

    public function get($id): ?ListenerEntity
    {
        return $this->io->find($id);
    }

    public function getForProject($projectId, $config = [])
    {
        $result = DB::table(self::TABLE)
            ->where('project_id', '=', $projectId)
            ->get();

        return $this->io->selectList($result);
    }

    public function getByUuid($uuid): ?ListenerEntity
    {
        $result = DB::table(self::TABLE)
            ->where('uuid', '=', $uuid)
            ->first();
        if ($result === null) {
            return null;
        }

        return $this->io->select($result);
    }

    public function insert(ListenerEntity $entity): ListenerEntity
    {
        return $this->io->insert($entity);
    }

    public function update($id, ListenerEntity $entity): ListenerEntity
    {
        return $this->io->update($id, $entity);
    }

    public function delete(ListenerEntity $entity)
    {
        $this->io->delete($entity->getId());
    }
}