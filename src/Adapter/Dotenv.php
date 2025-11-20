<?php

declare(strict_types=1);

namespace Vortex\Config\Adapter;

use Vortex\Config\Adapter;
use Vortex\Config\Exceptions\ParseException;

class Dotenv extends Adapter
{
    public function parse(string $contents): array
    {
        $config = [];

        $lines = explode("\n", $contents);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $pair = strstr($line, '#', true) ?: $line;
            $pair = trim($pair);

            [$key, $value] = explode('=', $pair, 2);

            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                throw new ParseException("Invalid dotenv line: $line");
            }

            $lower = strtolower($value);
            $value = match (true) {
                $lower === 'true' => true,
                $lower === 'false' => false,
                is_numeric($value) => $value + 0,
                default => $value,
            };
            $config[$key] = $value;
        }

        return $config;
    }
}
