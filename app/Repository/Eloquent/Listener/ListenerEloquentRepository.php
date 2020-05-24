<?php

namespace App\Repository\Eloquent\Listener;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerRepository;
use App\Repository\Eloquent\Rule\Rule;
use App\Repository\Eloquent\Rule\RuleEloquentWriteAdapter;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;

class ListenerEloquentRepository implements ListenerRepository
{
    private $readAdapter;
    private $writeAdapter;
    private $ruleWriteAdapter;

    public function __construct()
    {
        $this->readAdapter = new ListenerEloquentReadAdapter();
        $this->writeAdapter = new ListenerEloquentWriteAdapter();
        $this->ruleWriteAdapter = new RuleEloquentWriteAdapter();
    }

    public function getForProject($projectId)
    {
        $result = Listener::where('project_id', '=', $projectId)
            ->get();

        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
    }

    public function insert(ListenerEntity $entity): ListenerEntity
    {
        return $this->save($entity);
    }

    public function update($id, ListenerEntity $entity): ListenerEntity
    {
        $listener = Listener::find($id);
        $listener->project_id = $entity->getProjectId();
        $listener->name = $entity->getName();
        $listener->save();

        Rule::where('listener_id', $id)
            ->delete();

        $rules = [];
        foreach ($entity->getRules() as $rule) {
            $rules[] = $this->ruleWriteAdapter->adapt($rule);
        }
        $listener->rules()->saveMany($rules);

        return $this->readAdapter->adapt($listener);
    }

    private function save(ListenerEntity $entity)
    {
        $listener = $this->writeAdapter->adapt($entity);
        $listener->save();

        $rules = [];
        foreach ($entity->getRules() as $rule) {
            $rules[] = $this->ruleWriteAdapter->adapt($rule);
        }
        $listener->rules()->saveMany($rules);

        return $this->readAdapter->adapt($listener);
    }

    public function getByUuid($uuid): ?ListenerEntity
    {
        $result = Listener::where('uuid', '=', $uuid)
            ->first();
        return $this->readAdapter->adapt($result);
    }

    public function delete(ListenerEntity $entity)
    {
        Listener::destroy($entity->getId());
    }
}