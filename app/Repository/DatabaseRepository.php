<?php

namespace App\Repository;

use App\Domain\Handler\DatabaseHandlerRepository;
use App\Domain\Project\DatabaseProjectRepository;

class DatabaseRepository extends SimpleRepository
{
    public function __construct()
    {
        parent::__construct(
            new DatabaseProjectRepository(),
            new DatabaseHandlerRepository()
        );
    }
}