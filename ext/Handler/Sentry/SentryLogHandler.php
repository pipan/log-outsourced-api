<?php

namespace Ext\Handler\Sentry;

use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandler;
use Exception;
use Psr\Log\LogLevel;
use Sentry\Integration\RequestIntegration;
use Sentry\State\Scope;

use function Sentry\captureEvent;
use function Sentry\init;
use function Sentry\configureScope;

class SentryLogHandler implements LogHandler
{
    private $levelAdapter;
    private $allowedTags;

    public function __construct()
    {
        $this->levelAdapter = new LevelAdapter();
        $this->allowedTags = ['url', 'method', 'server', 'runtime', 'ip', 'user_agent'];
    }

    public function handle($log, ProjectEntity $project, $config)
    {
        $context = $log['context'] ?? [];
        $level = $this->levelAdapter->adapt($log['level']);
        $options = [
            'dsn' => $config['sentry_dsn'],
            'default_integrations' => false,
            'integrations' => [
                new RequestIntegration(null, new SourceRequestFetcher($context))
            ]
        ];
        if (isset($config['sentry_environment'])) {
            $options['environment'] = $config['sentry_environment'];
        }
        $tags = [];
        foreach ($this->allowedTags as $tagName) {
            if (!isset($context[$tagName])) {
                continue;
            }
            $tags[$tagName] = $context[$tagName];
        }
        init($options);
        configureScope(function (Scope $scope) use ($level, $tags, $context) {
            $scope->setLevel($level);
            $scope->setTags($tags);
            $scope->setContext('context', $context);
        });
        captureEvent([
            'message' => $log['message'],
            'context' => $context
        ]);
    }
}