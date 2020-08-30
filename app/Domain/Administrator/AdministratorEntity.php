<?php

namespace App\Domain\Administrator;

use Illuminate\Support\Facades\Hash;

class AdministratorEntity
{
    protected $id;
    protected $username;
    protected $passwordHash;
    protected $inviteToken;

    public function __construct($id, $username, $passwordHash, $inviteToken)
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->inviteToken = $inviteToken;
    }

    public static function createInvite($id, $username, $invoteToken)
    {
        return new AdministratorEntity($id, $username, "", $invoteToken);
    }

    public static function create($id, $username, $passwordHash)
    {
        return new AdministratorEntity($id, $username, $passwordHash, "");
    }

    public static function createWithPassword($id, $username, $password)
    {
        $passwordHash = Hash::make($password);
        return self::create($id, $username, $passwordHash);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getInviteToken()
    {
        return $this->inviteToken;
    }
}