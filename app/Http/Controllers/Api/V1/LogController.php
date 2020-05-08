<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Project\ProjectAdapter;
use App\Repository\Repository;
use Illuminate\Http\Request;

class ProjectController
{
    public function __construct()
    {
        $this->projectAdapter = new ProjectAdapter();
    }

    public function single(Request $request, Repository $repository)
    {
        $repository->project()->getAll();
    }

    public function batch(Request $request, Repository $repository)
    {
        $repository->project()->getAll();
    }
}