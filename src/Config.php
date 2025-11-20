<?php

declare(strict_types=1);

namespace Vortex\Config;

use Vortex\Config\Exceptions\LoadException;
use Vortex\Config\Exceptions\NotFoundException;

class Config
{
    /**
     * @var array<string, mixed>
     */
    private static array $container = [];

    private static bool $strict = false;

    /**
     * Load config file
     *
     * @throws LoadException
     */
    public static function load(string $key, string $path, Adapter $adapter): void
    {
        if (! is_readable($path)) {
            throw new LoadException("Failed to load configuration file: $path");
        }

        self::$container[$key] = $adapter->load($path);
    }

    public static function withStrict(bool $enable = true): void
    {
        self::$strict = $enable;
    }

    public static function set(string $key, mixed $value): void
    {
        self::$container[$key] = $value;
    }

    /**
     * @throws NotFoundException
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $data = self::$container;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($data) || ! array_key_exists($segment, $data)) {
                if (self::$strict) {
                    throw new NotFoundException("Configuration key '$key' does not exist.");
                }

                return $default;
            }
            $data = $data[$segment];
        }

        return $data;
    }
}
