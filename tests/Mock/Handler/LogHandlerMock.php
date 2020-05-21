<?php

namespace Tests\Mock\Handler;

use App\Handler\LogHandler;
use Tests\Mock\Mocker;

class LogHandlerMock implements LogHandler
{
    private $mocker;

    public function __construct()
    {
        $this->mocker = new Mocker();
    }

    public function getMocker(): Mocker
    {
        return $this->mocker;
    }

    public function handle($log, $config)
    {
        return $this->mocker->getSimulation('handle')
            ->execute([$log, $config]);
    }
}