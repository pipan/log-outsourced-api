<?php

require_once '../current/public/main.php';
$config = require '../environment/config.php';

OutsourcedLog::artisan($config);
