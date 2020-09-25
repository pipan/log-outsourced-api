<?php

namespace Lib\Hook;

class HookChain implements ActionHook
{
    private $hooks;

    public function __construct($hooks = [])
    {
        $this->hooks = $hooks;
    }

    public function onAction($item)
    {
        foreach ($this->hooks as $hook) {
            $item = $hook->onAction($item);
        }
        return $item;
    }

    public function add(ActionHook $hook)
    {
        $this->hooks[] = $hook;
    }
}