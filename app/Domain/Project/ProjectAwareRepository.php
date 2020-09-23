<?php

namespace App\Domain\Project;

interface ProjectAwareRepository
{
    public function getForProject($projectId, $config);
}