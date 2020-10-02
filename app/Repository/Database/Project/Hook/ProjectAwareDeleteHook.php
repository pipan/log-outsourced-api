<?php

namespace App\Repository\Database\Project\Hook;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class ProjectAwareDeleteHook implements ActionHook
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function onAction($item)
    {
        DB::table($this->table)
            ->where('project_id', '=', $item->getId())
            ->delete();
        
        return $item;
    }
}