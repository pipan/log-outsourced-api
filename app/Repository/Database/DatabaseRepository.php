<?php

namespace App\Repository\Database;

use App\Repository\Database\Config\ConfigDatabaseRepository;
use App\Repository\Database\Handler\DatabaseHandlerRepository;
use App\Repository\Database\Project\DatabaseProjectRepository;
use App\Repository\Database\Setting\SettingDatabaseRepository;
use App\Repository\SimpleRepository;

class DatabaseRepository extends SimpleRepository
{
    public function __construct()
    {
        parent::__construct(
            new DatabaseProjectRepository(),
            new DatabaseHandlerRepository(),
            new SettingDatabaseRepository(),
            new ConfigDatabaseRepository()
        );
    }
}