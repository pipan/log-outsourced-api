<?php

namespace App\Repository;

use App\Domain\Handler\HandlerRepository;
use App\Domain\Project\ProjectRepository;

class SimpleRepository implements Repository
{
    protected $projectRepo;
    protected $handlerRepo;

    public function __construct(
        $projectRepo,
        $handlerRepo
    )
    {
        $this->projectRepo = $projectRepo;
        $this->handlerRepo = $handlerRepo;
    }

    public function project(): ProjectRepository
    {
        return $this->projectRepo;
    }

    public function handler(): HandlerRepository
    {
        return $this->handlerRepo;
    }
}