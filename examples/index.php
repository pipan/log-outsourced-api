<?php

require_once readlink('../current') . '/public/main.php';
$config = require '../environment/config.php';

OutsourcedLog::main($config);
