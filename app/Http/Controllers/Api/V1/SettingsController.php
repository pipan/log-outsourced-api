<?php

namespace App\Http\Controllers\Api\V1;

use App\Repository\Repository;

class SettingsController
{
    public function view($hexUuid, Repository $repository)
    {
        return response()->json(null, 200);
    }
}