<?php
require __DIR__.'/../vendor/autoload.php';
define('LARAVEL_START', microtime(true));

class OutsourcedLog
{
    public static function main($config = []) {
        $app = self::createApp($config);
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

        $response = $kernel->handle(
            $request = Illuminate\Http\Request::capture()
        );

        $response->send();

        $kernel->terminate($request, $response);
    }

    public static function artisan($config = []) {
        $app = self::createApp($config);
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

        $status = $kernel->handle(
            $input = new Symfony\Component\Console\Input\ArgvInput,
            new Symfony\Component\Console\Output\ConsoleOutput
        );

        $kernel->terminate($input, $status);

        exit($status);
    }

    private static function createApp($config = []) {
        $app = require_once __DIR__.'/../bootstrap/app.php';

        if (isset($config['env_path'])) {
            $app->useEnvironmentPath($config['env_path']);
        }
        if (isset($config['storage_path'])) {
            $app->useStoragePath($config['storage_path']);
        }
        if (isset($config['release_path'])) {
            $app->useReleasePath($config['release_path']);
        }
        return $app;
    }
}
