<?php

namespace App\Handler;

class LogHandlerContainer
{
    private $handlers = [];

    public function add($name, LogHandler $handler)
    {
        $this->handlers[$name] = $handler;
    }

    public function get($name): ?LogHandler
    {
        if (!isset($this->handlers[$name])) {
            return null;
        }
        return $this->handlers[$name];
    }
}