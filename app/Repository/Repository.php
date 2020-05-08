<?php

namespace App\Repository;

use App\Domain\Handler\HandlerRepository;
use App\Domain\Project\ProjectRepository;

interface Repository
{
    public function project(): ProjectRepository;
    public function handler(): HandlerRepository;
}