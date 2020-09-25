<?php

namespace App\Domain\Project;

use App\Domain\ExistsValidable;

class UuidExistsValidator implements ExistsValidable
{
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function exists($value)
    {
        return $this->projectRepository->getByUuid($value) !== null;
    }
}