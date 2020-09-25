<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Mock\Repository\HandlerMockRepository;

class HandlerTestSeeder
{
    public static function seed(HandlerMockRepository $repository)
    {
        $repository->getMocker()
            ->getSimulation('getBySlug')
            ->whenInputReturn(
                new HandlerEntity('file', 'file', []),
                ['file']
            );
    }
}