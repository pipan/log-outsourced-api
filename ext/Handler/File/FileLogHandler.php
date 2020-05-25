<?php

namespace Ext\Handler\File;

use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandler;
use Illuminate\Support\Facades\Storage;

class FileLogHandler implements LogHandler
{
    public function handle($log, ProjectEntity $project, $config)
    {
        $fileName = $log['level'] . ".log";
        if (isset($config['file_daily']) && $config['file_daily']) {
            $fileName = date('Y-m-d') . '_' . $fileName;
        }
        $content = "[" . date("Y-m-d H:i:s") . "] " . $log['message'] . " " . json_encode($log['context'] ?? []);
        Storage::disk('local')->append('projects/' . $project->getId() . '/' . $fileName, $content);
    }
}