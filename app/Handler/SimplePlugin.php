<?php

namespace App\Handler;

use App\Domain\Handler\HandlerEntity;
use App\Repository\Repository;
use Illuminate\Contracts\Foundation\Application;

class SimplePlugin implements Plugin
{
    protected $handler;

    public function __construct(HandlerEntity $handler)
    {
        $this->handler = $handler;
    }

    public function connect(Application $app)
    {
        $app->make(Repository::class)->handler()
            ->insert($this->handler);
    }

    public function disconnect(Application $app)
    {
        $app->make(Repository::class)->handler()
            ->delete($this->handler);
    }
}