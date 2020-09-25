<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Tests\Mock\Repository\ListenerMockRepository;

class ListenerTestSeeder
{
    public static function seed(ListenerMockRepository $repository)
    {
        $repository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ListenerEntity(1, 'aabb', 1, 'name', [], 'slug', encrypt([])),
                ['aabb']
            );
    }
}