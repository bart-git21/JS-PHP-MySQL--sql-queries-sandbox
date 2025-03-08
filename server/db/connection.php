<?php
// access the environment variables
require_once __DIR__ . '/../../vendor/autoload.php';
(new App\Controller\DotEnvEnvironment)->load(__DIR__ . '/../../');
