<?php
require_once __DIR__ . '/../vendor/autoload.php';
(new App\Controller\DotEnvEnvironment)->load(__DIR__ . '/../');
echo $_ENV['DATABASE'];