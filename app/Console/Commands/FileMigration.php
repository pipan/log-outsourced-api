<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FileMigration extends Command
{
    protected $signature = 'init:file';

    protected $description = 'Initialize file storage';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (!Storage::disk('local')->exists('data')) {
            Storage::disk('local')->makeDirectory('data');
        }

        $files = [
            'data/increment.json',
            'data/projects.json',
            'data/listeners.json'
        ];

        foreach ($files as $file) {
            if (Storage::disk('local')->exists($file)) {
                continue;
            }
            Storage::disk('local')->put($file, json_encode([]));
        }
    }
}
