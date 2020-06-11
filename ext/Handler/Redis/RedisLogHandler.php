<?php

namespace Ext\Handler\Redis;

use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandler;
use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Redis\Connectors\PhpRedisConnector;

class RedisLogHandler implements LogHandler
{
    public function handle($log, ProjectEntity $project, $config)
    {
        $connection = $this->createConnection($config);
        $connection->rPush($log['level'], json_encode($log));
        $connection->close();
    }

    private function createConnection($config): PhpRedisConnection
    {
        $params = [
            'url' => $config['redis_url'],
            'host' => $config['redis_host'],
            'password' => $config['redis_password'] ?? '',
            'port' => $config['redis_port'],
            'database' => $config['redis_database']
        ];
        $connector = new PhpRedisConnector();
        return $connector->connect($params, []);
    }
}