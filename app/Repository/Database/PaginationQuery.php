<?php

namespace App\Repository\Database;

use App\Repository\Database\QueryExtension\ChainQueryExtension;
use App\Repository\Database\QueryExtension\OrderQueryExtension;
use App\Repository\Database\QueryExtension\PaginationQueryExtension;
use App\Repository\Database\QueryExtension\SearchQueryExtension;
use Lib\Pagination\PaginationEntity;

class PaginationQuery
{
    public static function getExtensionForEntity(PaginationEntity $entity)
    {
        return new ChainQueryExtension([
            new PaginationQueryExtension($entity),
            self::getSearchExtension($entity->getSearchBy(), $entity->getSearchValue()),
            new OrderQueryExtension($entity)
        ]);
    }

    public static function getSearchExtension($columnName, $value)
    {
        return new SearchQueryExtension($columnName, $value);
    }
}