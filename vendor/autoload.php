<?php
namespace App\Controller;

class DotEnvEnvironment
{
    public function load($path): void
    {
        if (!file_exists(filename: $path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('%s file is not readable', $path));
        }
        $lines = file($path . '/.env');
        foreach ($lines as $line) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (!array_key_exists($key, $_ENV) && !array_key_exists($key, $_SERVER)) {
                putenv(sprintf('%s=%s', $key, $value));
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}