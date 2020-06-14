<?php

require_once "main.php";

OutsourcedLog::main([
    'env_path' => $_SERVER['DOCUMENT_ROOT'] . '/..',
    'release_path' => 'aaaa'
]);
