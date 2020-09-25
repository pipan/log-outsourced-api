<?php

namespace App\Repository\Database\Rule\Hook\Listener;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class ListenerDeleteHook implements ActionHook
{
    public function onAction($listener)
    {
        DB::table('rules')
            ->where('listener_id', '=', $listener->getId())
            ->delete();
        
        return $listener;
    }
}