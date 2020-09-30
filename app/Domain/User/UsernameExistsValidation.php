<?php

namespace App\Domain\User;

use App\Domain\ExistsValidable;

class UsernameExistsValidation implements ExistsValidable
{
    private $repository;
    private $projectId;

    public function __construct(UserRepository $repository, $projectId)
    {
        $this->repository = $repository;
        $this->projectId = $projectId;
    }

    public function exists($value)
    {
        return $this->repository->getByUsernameForProject($value, $this->projectId) !== null;
    }
}