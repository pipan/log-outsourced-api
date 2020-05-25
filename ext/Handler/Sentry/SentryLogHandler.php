<?php

namespace Ext\Handler\Sentry;

use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandler;
use Exception;
use Psr\Log\LogLevel;
use Sentry\State\Scope;

use function Sentry\captureEvent;
use function Sentry\init;
use function Sentry\configureScope;

class SentryLogHandler implements LogHandler
{
    private $levelAdapter;

    public function __construct()
    {
        $this->levelAdapter = new LevelAdapter();
    }

    public function handle($log, ProjectEntity $project, $config)
    {
        $level = $this->levelAdapter->adapt($log['level']);
        $options = [
            'dsn' => $config['sentry_dsn']
        ];
        if (isset($config['sentry_environment'])) {
            $options['environment'] = $config['sentry_environment'];
        }
        init($options);
        configureScope(function (Scope $scope) use ($level) {
            $scope->setLevel($level);
        });
        captureEvent([
            'message' => $log['message'],
            'context' => $log['context'] ?? []
        ]);
    }
}