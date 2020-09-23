<?php

namespace App\Domain\Project;

class ProjectAwareEntity
{
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function getProjectId()
    {
        return $this->projectId;
    }
}