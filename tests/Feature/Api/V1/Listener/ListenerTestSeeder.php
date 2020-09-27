<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Tests\Mock\Repository\ListenerMockRepository;

class ListenerTestSeeder
{
    public static function seed(ListenerMockRepository $repository)
    {
        $listener = new ListenerEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'name',
            'rules' => [],
            'handler_slug' => 'slug',
            'handler_values' => []
        ]);
        $repository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn($listener, ['aabb']);
    }
}