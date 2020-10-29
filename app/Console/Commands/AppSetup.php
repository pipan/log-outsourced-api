<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class AppSetup extends Command
{
    protected $signature = 'setup';

    protected $description = 'Setup application';

    public function handle()
    {
        $input = [];
        $currentUrl = env('APP_URL');
        $input['app_url'] = $this->ask('App URL:', $currentUrl);
        
        $currentRootUsername = env('ROOT_USERNAME');
        $input['root_username'] = $this->ask('Root username:', $currentRootUsername);
        
        $selectPassword = $this->ask('Select new root password?', 'n');
        $password = null;
        if ($selectPassword === 'y') {
            $newPassword = $this->secret('Root password:');
            $repeatPassword = $this->secret('Repeat root password:');
            if (!$newPassword) {
                $this->warn("Password cannot be empty string");
            } else {
                if ($newPassword !== $repeatPassword) {
                    $this->warn("Passwords do not match.");
                } else {
                    $password = $newPassword;
                }
            }
        }

        foreach ($input as $key => $value) {
            Artisan::call('env:set ' . $key . ' "' . $value . '"');
        }
        
        if ($password) {
            $passwordHash = base64_encode(Hash::make($password));
            Artisan::call("env:set root_password " . $passwordHash);
        }
    }
}
