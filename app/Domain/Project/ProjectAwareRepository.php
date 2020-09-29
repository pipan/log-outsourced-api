<?php

namespace App\Domain\Project;

use Lib\Pagination\PaginationEntity;

interface ProjectAwareRepository
{
    public function getForProject($projectId, PaginationEntity $pagination);
    public function countForProject($projectId, $search);
}