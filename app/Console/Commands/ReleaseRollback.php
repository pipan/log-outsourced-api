<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class ReleaseRollback extends Command
{
    protected $signature = 'release:rollback';

    protected $description = 'Rollback to previous release';

    public function handle()
    {
        $currentPath = $this->laravel['path.root'] . '/current';
        if (!file_exists($currentPath)) {
            $this->error('link to current release does not exists');
            return;
        }

        $this->info("Rolling back to previous version");

        $releasesPath = $this->laravel['path.root'] . '/releases';
        $currentVersion = (int) basename(readlink($currentPath));
        $prevVersion = $currentVersion - 1;
        for ($prevVersion; $prevVersion > 0; $prevVersion--) {
            if (!file_exists($releasesPath . '/' . $prevVersion)) {
                continue;
            }
            exec('ln -sfn ' . $releasesPath . DIRECTORY_SEPARATOR . $prevVersion . ' ' . $currentPath);
            exec('rm -rf ' . $releasesPath . DIRECTORY_SEPARATOR . $currentVersion);
            $this->info('Rollback successful');
            return;
        }
        $this->info('Cannot rollback: no previous version found');
    }
}
