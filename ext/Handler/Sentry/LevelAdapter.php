<?php

namespace Ext\Handler\Sentry;

use Lib\Adapter\Adapter;
use Psr\Log\LogLevel;
use Sentry\Severity;

class LevelAdapter implements Adapter
{
    private $mapping = [
        LogLevel::DEBUG => Severity::DEBUG,
        LogLevel::INFO => Severity::INFO,
        LogLevel::NOTICE => Severity::INFO,
        LogLevel::WARNING => Severity::WARNING,
        LogLevel::ERROR => Severity::ERROR,
        LogLevel::CRITICAL => Severity::ERROR,
        LogLevel::ALERT => Severity::FATAL,
        LogLevel::EMERGENCY => Severity::FATAL
    ];

    public function adapt($item)
    {
        if (!isset($this->mapping[$item])) {
            return null;
        }
        return new Severity($this->mapping[$item]);
    }
}