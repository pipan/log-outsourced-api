<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DatabaseSetup extends Command
{
    protected $signature = 'setup:database';

    protected $description = 'Setup database';

    public function handle()
    {
        $input = [];
        $current = env('DB_CONNECTION', 'mysql');
        $input['db_connection'] = $this->ask('Connection:', $current);

        $current = env('DB_HOST', '');
        $input['db_host'] = $this->ask('Host:', $current);

        $current = env('DB_PORT', '3306');
        $input['db_port'] = $this->ask('Port:', $current);

        $current = env('DB_DATABASE', '');
        $input['db_database'] = $this->ask('Database Name:', $current);

        $current = env('DB_USERNAME', '');
        $input['db_username'] = $this->ask('Username:', $current);

        $selectPassword = $this->ask('Select new password?', 'n');
        $password = null;
        if ($selectPassword === 'y') {
            $newPassword = $this->secret('Root password:') ?? '';
            $repeatPassword = $this->secret('Repeat root password:') ?? '';
            if ($newPassword !== $repeatPassword) {
                $this->warn("Passwords do not match.");
            } else {
                $password = $newPassword;
            }
        }

        foreach ($input as $key => $value) {
            Artisan::call('env:set ' . $key . ' "' . $value . '"');
        }

        if ($password !== null) {
            Artisan::call('env:set db_password "' . $password . '"');
        }
    }
}
