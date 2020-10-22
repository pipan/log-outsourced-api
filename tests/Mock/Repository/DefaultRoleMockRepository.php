<?php

namespace Tests\Mock\Repository;

use App\Domain\Settings\DefaultRole\DefaultRoleRepository;
use Tests\Mock\Mocker;

class DefaultRoleMockRepository implements DefaultRoleRepository
{
    private $mocker;

    public function __construct()
    {
        $this->mocker = new Mocker();
    }

    public function getMocker(): Mocker
    {
        return $this->mocker;
    }

    public function get($projectId)
    {
        return $this->mocker->getSimulation('get')
            ->execute([$projectId]);
    }

    public function set($projectId, $roles)
    {
        return $this->getMocker()->getSimulation('set')
            ->execute([$projectId, $roles]);
    }
}