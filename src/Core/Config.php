<?php

declare(strict_types=1);

namespace Starfall\Core;

use RuntimeException;

final class Config
{
    /** @var array<string,mixed> */
    private array $cache = [];

    public function __construct(private readonly string $configDirectory)
    {
    }

    /** @return array<string,mixed> */
    public function get(string $name): array
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $path = $this->configDirectory . DIRECTORY_SEPARATOR . $name . '.php';
        if (!is_file($path)) {
            throw new RuntimeException('Missing config file: ' . $path);
        }

        $config = require $path;
        if (!is_array($config)) {
            throw new RuntimeException('Config must return an array: ' . $path);
        }

        return $this->cache[$name] = $config;
    }
}
