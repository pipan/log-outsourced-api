<?php

namespace App\Repository\Database\Rule\Hook\Listener;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class ListenerSaveHook implements ActionHook
{
    public function onAction($listener)
    {
        DB::table('rules')
            ->where('listener_id', '=', $listener->getId())
            ->delete();

        $rules = [];
        foreach ($listener->getRules() as $rule) {
            $rules[] = [
                'listener_id' => $listener->getId(),
                'pattern' => $rule
            ];
        }
        DB::table('rules')
            ->insert($rules);

        return $listener;
    }
}