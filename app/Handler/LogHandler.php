<?php

namespace App\Handler;

use App\Domain\Project\ProjectEntity;

interface LogHandler
{
    public function handle($log, ProjectEntity $project, $config);
}