<?php

namespace Tests\Feature\Api\V1;

use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Mock\Repository\AdministratorMockRepository;
use Tests\Mock\Repository\HandlerMockRepository;
use Tests\Mock\Repository\ListenerMockRepository;
use Tests\Mock\Repository\ProjectMockRepository;
use Tests\Mock\Repository\RoleMockRepository;
use Tests\Mock\Repository\UserMockRepository;
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
    /** @var RoleMockRepository */
    protected $roleRepository;
    /** @var UserMockRepository */
    protected $userRepository;
    /** @var Repository */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepository = new ProjectMockRepository();
        $this->listenerRepository = new ListenerMockRepository();
        $this->handlerRepository = new HandlerMockRepository();
        $this->administratorRepository = new AdministratorMockRepository();
        $this->roleRepository = new RoleMockRepository();
        $this->userRepository = new UserMockRepository();
        $this->repository = new SimpleRepository(
            $this->projectRepository,
            $this->listenerRepository,
            $this->handlerRepository,
            $this->administratorRepository,
            $this->roleRepository,
            $this->userRepository
        );
        $this->app->instance(Repository::class, $this->repository);
    }
}