<?php

namespace App\Repository\File\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Lib\Adapter\Adapter;

class AdministratorFileReadAdapter implements Adapter
{
    public function adapt($item)
    {
        return new AdministratorEntity(
            $item['id'],
            $item['username'],
            $item['password_hash'] ?? "",
            $item['invite_token'] ?? ""
        );
    }
}