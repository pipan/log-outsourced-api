<?php

namespace App\Repository\Database\Role\Hook\User;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class UserDeleteHook implements ActionHook
{
    public function onAction($user)
    {
        DB::table('roles_users')
            ->where('user_id', '=', $user->getId())
            ->delete();
        
        return $user;
    }
}