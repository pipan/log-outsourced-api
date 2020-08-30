<?php

namespace Tests\Feature\Api\V1;

use App\Domain\Administrator\AdministratorRepository;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Mockery;
use Mockery\MockInterface;
use Tests\Mock\Repository\AdministratorMockRepository;
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
    /** @var AdministratorMockRepository */
    protected $administratorRepository;
    /** @var Repository */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepository = new ProjectMockRepository();
        $this->listenerRepository = new ListenerMockRepository();
        $this->handlerRepository = new HandlerMockRepository();
        $this->administratorRepository = new AdministratorMockRepository();
        $this->repository = new SimpleRepository(
            $this->projectRepository,
            $this->listenerRepository,
            $this->handlerRepository,
            $this->administratorRepository
        );
        $this->app->instance(Repository::class, $this->repository);
    }
}