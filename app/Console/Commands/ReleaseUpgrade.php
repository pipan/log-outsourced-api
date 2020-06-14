<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReleaseUpgrade extends Command
{
    protected $signature = 'release:upgrade';

    protected $description = 'Upgrade to next release';

    public function handle()
    {
        if (!isset($this->laravel['path.root'])) {
            $this->error('application does not have root path set');
            return;
        }
        $currentPath = $this->laravel['path.root'] . DIRECTORY_SEPARATOR . 'current';
        $this->info($currentPath);
        if (!file_exists($currentPath)) {
            $this->error('link to current release does not exists');
            return;
        }

        $this->info("Upgrading to next release");
        $releasesPath = $this->laravel['path.root'] . DIRECTORY_SEPARATOR . 'releases';
        $currentVersion = (int) basename(readlink($currentPath));
        $nextVersion = $currentVersion + 1;

        $cmd = 'composer create-project --no-dev --prefer-dist outsourced/log ' . $releasesPath . DIRECTORY_SEPARATOR . $nextVersion;
        $this->info('executing: ' . $cmd);
        exec($cmd);
        exec('ln -sfn ' . $releasesPath . DIRECTORY_SEPARATOR . $nextVersion . ' ' . $currentPath);
        $this->info("Upgrade successful");
    }
}
