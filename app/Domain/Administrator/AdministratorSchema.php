<?php

namespace App\Domain\Administrator;

use Lib\Entity\EntityBlacklistAdapter;
use Lib\Entity\EntityWhitelistAdapter;

class AdministratorSchema
{
    public static function forPublic()
    {
        return new EntityWhitelistAdapter(['uuid', 'username', 'invite_token']);
    }

    public static function forWriting()
    {
        return new EntityBlacklistAdapter(['id']);
    }
}