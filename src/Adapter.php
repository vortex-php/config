<?php

declare(strict_types=1);

namespace Vortex\Config;

use Vortex\Config\Exceptions\LoadException;
use Vortex\Config\Exceptions\ParseException;

abstract class Adapter
{
    /**
     * @return array<string, mixed>
     *
     * @throws ParseException
     */
    abstract public function parse(string $contents): array;

    /**
     * @return array<string, mixed>
     *
     * @throws LoadException
     */
    public function load(string $path): array
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new LoadException("Config not found: $path");
        }

        return $this->parse($contents);
    }
}
