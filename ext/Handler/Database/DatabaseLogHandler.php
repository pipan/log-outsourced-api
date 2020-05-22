<?php

namespace Ext\Handler\Database;

use App\Handler\LogHandler;
use Exception;
use PDO;
use PDOException;

class DatabaseLogHandler implements LogHandler
{
    public function handle($log, $config)
    {
        $connection = $this->createConnection($config);
        $statement = $connection->prepare("INSERT INTO " . $config['db_table'] . "(level, message, context) VALUES(:level, :message, :context)");

        $result = $statement->execute([
            ':level' => $log['level'],
            ':message' => $log['message'],
            ':context' => json_encode($log['context'] ?? [])
        ]);

        if ($result === false) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("Cannot save log to database: " . json_encode($errorInfo));
        }

        $statement = null; // closing statement
        $connection = null; // closing connection
    }

    private function createConnection($config): PDO
    {
        $params = [
            'dbname=' . $config['db_database'],
            'host=' . $config['db_host'],
            'port=' . $config['db_port']
        ];
        $dsn = $config['db_driver'] . ":" . implode(";", $params);

        return new PDO($dsn, $config['db_user'], $config['db_password']);
    }
}