<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AppSetup extends Command
{
    protected $signature = 'setup';

    protected $description = 'Setup application';

    public function handle()
    {
        $input = [];
        $currentUrl = env('APP_URL');
        $input['url'] = $this->ask('What is the API url? [' . $currentUrl . ']');
        
        $currentRootUsername = env('ROOT_USERNAME');
        $input['username'] = $this->ask('Selet root username? [' . $currentRootUsername . ']');
        
        $input['password'] = $this->secret('Select password for root? [keep existing]');
        if ($input['password']) {
            $input['password_repeat'] = $this->secret('Repeat password for root?');
            if ($input['password'] !== $input['password_repeat']) {
                $input['password'] = null;
                $this->warn("Passwords do not match.");
            }
        }

        if ($input['url']) {
            Artisan::call('env:set app_url ' . $input['url']);
        }
        if ($input['username']) {
            Artisan::call('env:set root_username ' . $input['username']);
        }
        if ($input['password']) {
            $passwordHash = base64_encode(Hash::make($input['password']));
            Artisan::call("env:set root_password " . $passwordHash);
        }
    }
}
