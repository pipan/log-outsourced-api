<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Lib\Pagination\PaginationEntity;
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

    public static function seedForProject(ListenerMockRepository $repository)
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('name')
            ->orderBy('name');
        $listener = new ListenerEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'error mock',
            'rules' => ['error'],
            'handler_slug' => 'mock',
            'handler_values' => []
        ]);
        $repository->getMocker()
            ->getSimulation('getAllForProject')
            ->whenInputReturn([$listener], [1]);
        $repository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$listener], [1, $pagination]);
    }
}