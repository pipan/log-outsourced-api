<?php

namespace Tests\Feature\Api\V1;

use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Mock\Repository\HandlerMockRepository;
use Tests\Mock\Repository\ListenerMockRepository;
use Tests\Mock\Repository\ProjectMockRepository;
use Tests\TestCase;

abstract class ControllerActionTestCase extends TestCase
{
    /** @var ProjectMockRepository */
    protected $projectRepository;
    /** @var ListenerMockRepository */
    protected $listenerRepository;
    /** @var HandlerMockRepository */
    protected $handlerRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepository = new ProjectMockRepository();
        $this->listenerRepository = new ListenerMockRepository();
        $this->handlerRepository = new HandlerMockRepository();
        $this->app->instance(Repository::class, new SimpleRepository(
            $this->projectRepository,
            $this->listenerRepository,
            $this->handlerRepository
        ));
    }
}