<?php

namespace App\Domain\Administrator;

use App\Domain\ExistsValidable;

class UsernameExistsValidation implements ExistsValidable
{
    private $repository;

    public function __construct(AdministratorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function exists($value)
    {
        return $this->repository->getByUsername($value) !== null;
    }
}