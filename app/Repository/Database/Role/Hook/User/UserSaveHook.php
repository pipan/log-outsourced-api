<?php

namespace App\Repository\Database\Role\Hook\User;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class UserSaveHook implements ActionHook
{
    public function onAction($user)
    {
        DB::table('roles_users')
            ->where('user_id', '=', $user->getId())
            ->delete();

        $bindings = [];
        foreach ($user->getRoles() as $role) {
            $result = DB::table('roles')
                ->where('name', '=', $role)
                ->where('project_id', '=', $user->getProjectId())
                ->first();
            if ($result === null) {
                continue;
            }

            $roleId = $result->id;
            $bindings[] = [
                'user_id' => $user->getId(),
                'role_id' => $roleId
            ];

        }
        DB::table('roles_users')
            ->insert($bindings);

        return $user;
    }
}