<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class FileMigration extends Command
{
    protected $signature = 'setup';

    protected $description = 'Setup application';

    public function handle()
    {
        $url = $this->ask('What is the API url?');
        Artisan::call('env:set app_url ' . $url);
    }
}
