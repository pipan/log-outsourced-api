<?php

namespace App\Domain;

interface ExistsValidable
{
    public function exists($value);
}