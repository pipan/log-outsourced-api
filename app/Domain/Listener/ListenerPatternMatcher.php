<?php

namespace App\Domain\Listener;

class ListenerPatternMatcher
{
    public function match($pattern, $listeners)
    {
        $matched = [];
        foreach ($listeners as $listener) {
            $result = $this->isMatching($pattern, $listener);
            if (!$result) {
                continue;
            }
            $matched[] = $listener;
        }
        return $matched;
    }

    private function isMatching($pattern, ListenerEntity $listener)
    {
        foreach ($listener->getRules() as $rule) {
            if ($rule == $pattern) {
                return true;
            }
        }
        return false;
    }
}