<?php

namespace Tests\Mock;

class Simulation
{
    private $executions = [];
    private $cases = [];

    public function whenInputReturn($return, $inputArgs = [])
    {
        $this->cases[] = [
            'return' => $return,
            'input' => $inputArgs
        ];
    }

    public function execute($args = [])
    {
        $this->executions[] = $args;
        foreach ($this->cases as $case) {
            if ($case['input'] == $args) {
                return $case['return'];
            }
        }
        return;
    }

    public function getExecutions()
    {
        return $this->executions;
    }
}