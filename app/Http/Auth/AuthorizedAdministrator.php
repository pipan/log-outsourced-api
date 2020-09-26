<?php

namespace App\Http\Auth;

use App\Domain\Administrator\AdministratorEntity;
use App\Repository\Repository;
use Illuminate\Http\Request;

class AuthorizedAdministrator
{
    private $admin;

    public function __construct(Request $request, Repository $repository)
    {
        $authorization = new JwtAutorization($request);
        $id = $authorization->getId();
        $this->admin = $repository->administrator()->get($id);
    }

    public function getAdmimnistrator(): ?AdministratorEntity
    {
        return $this->admin;
    }
}