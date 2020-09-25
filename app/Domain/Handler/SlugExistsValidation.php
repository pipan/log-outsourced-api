<?php

namespace App\Domain\Handler;

use App\Domain\ExistsValidable;

class SlugExistsValidation implements ExistsValidable
{
    private $handlerRepository;

    public function __construct(HandlerRepository $handlerRepository)
    {
        $this->handlerRepository = $handlerRepository;
    }

    public function exists($value)
    {
        return $this->handlerRepository->getBySlug($value) !== null;
    }
}