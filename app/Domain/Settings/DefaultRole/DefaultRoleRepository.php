<?php

namespace App\Domain\Settings\DefaultRole;

interface DefaultRoleRepository
{
    public function get($projectId);
    public function set($projectId, $roles);
}