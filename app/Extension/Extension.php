<?php

namespace App\Extentions;

interface Extension
{
    public function install();
    public function uninstall();
}