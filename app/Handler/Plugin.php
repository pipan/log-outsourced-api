<?php

namespace App\Handler;

use Illuminate\Contracts\Foundation\Application;

interface Plugin
{
    public function connect(Application $app);
    public function disconnect(Application $app);
}