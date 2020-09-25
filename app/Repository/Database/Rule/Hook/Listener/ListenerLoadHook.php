<?php

namespace App\Repository\Database\Rule\Hook\Listener;

use App\Domain\Listener\ListenerEntity;
use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class ListenerLoadHook implements ActionHook
{
    public function onAction($listeners)
    {
        $ids = [];
        foreach ($listeners as $listener) {
            $ids[] = $listener->getId();
        }

        $result = DB::table('rules')
            ->whereIn('listener_id', $ids)
            ->get();

        $rules = [];
        foreach ($result as $item) {
            if (!isset($rules[$item->listener_id])) {
                $rules[$item->listener_id] = [];
            }
            $rules[$item->listener_id][] = $item->pattern;
        }

        $newListeners = [];
        foreach ($listeners as $listener) {
            if (!isset($rules[$listener->getId()])) {
                $newListeners[] = $listener;
                continue;
            }
            $newListeners[] = new ListenerEntity(
                $listener->getId(),
                $listener->getUuid(),
                $listener->getProjectId(),
                $listener->getName(),
                $rules[$listener->getId()],
                $listener->getHandlerSlug(),
                $listener->getHandlerValues()
            );
        }

        return $newListeners;
    }
}