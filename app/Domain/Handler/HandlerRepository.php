<?php

namespace App\Domain\Handler;

interface HandlerRepository
{
    public function getBySlug($slug);
    public function get($id);
    public function getAll();
}