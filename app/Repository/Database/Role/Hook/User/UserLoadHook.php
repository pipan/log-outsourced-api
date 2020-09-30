<?php

namespace App\Repository\Database\Role\Hook\User;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class UserLoadHook implements ActionHook
{
    public function onAction($users)
    {
        $ids = [];
        foreach ($users as $user) {
            $ids[] = $user->getId();
        }

        $result = DB::table('roles_users')
            ->join('roles', 'roles_users.role_id', '=', 'roles.id')
            ->whereIn('roles_users.user_id', $ids)
            ->get();

        $roles = [];
        foreach ($result as $item) {
            if (!isset($roles[$item->user_id])) {
                $roles[$item->user_id] = [];
            }
            $roles[$item->user_id][] = $item->name;
        }

        $newUsers = [];
        foreach ($users as $user) {
            if (!isset($roles[$user->getId()])) {
                $newUsers[] = $user;
                continue;
            }
            $newUsers[] = $user->withRoles(
                $roles[$user->getId()]
            );
        }

        return $newUsers;
    }
}