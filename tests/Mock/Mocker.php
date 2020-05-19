<?php

namespace Tests\Mock;

class Mocker
{
    private $simulations = [];

    public function getSimulation($methodName): Simulation {
        if (!isset($this->simulations[$methodName])) {
            $this->simulations[$methodName] = new Simulation();
        }
        return $this->simulations[$methodName];
    }
}