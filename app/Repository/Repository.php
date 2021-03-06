<?php

namespace App\Repository;

use App\Domain\Administrator\AdministratorRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Permission\PermissionRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Role\RoleRepository;
use App\Domain\Settings\DefaultRole\DefaultRoleRepository;
use App\Domain\Settings\ProjectKey\ProjectKeyRepository;
use App\Domain\User\UserRepository;

interface Repository
{
    public function project(): ProjectRepository;
    public function listener(): ListenerRepository;
    public function handler(): HandlerRepository;
    public function administrator(): ?AdministratorRepository;
    public function role(): ?RoleRepository;
    public function user(): ?UserRepository;
    public function permission(): ?PermissionRepository;
    public function defaultRole(): ?DefaultRoleRepository;
    public function projectKey(): ?ProjectKeyRepository;
}