<?php

declare(strict_types=1);

namespace Vortex\Config\Adapter;

use Vortex\Config\Adapter;
use Vortex\Config\Exceptions\LoadException;
use Vortex\Config\Exceptions\ParseException;

class PHP extends Adapter
{
    /**
     * @return array|mixed[]
     *
     * @throws ParseException
     */
    public function parse(string $contents): array
    {
        throw new ParseException('PHP config only supports loading.');
    }

    /**
     * @return array|mixed[]
     *
     * @throws LoadException
     */
    public function load(string $path): array
    {
        if (is_file($path)) {
            $data = include_once $path;
            if (! is_array($data)) {
                throw new LoadException('PHP config must return array.');
            }

            return $data;
        }

        $config = [];

        if (is_dir($path)) {
            $files = glob(rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'*.php') ?: [];

            foreach ($files as $file) {
                $key = pathinfo($file, PATHINFO_FILENAME);
                $data = include_once $file;
                if (! is_array($data)) {
                    throw new LoadException('PHP config must return array.');
                }
                $config[$key] = $data;
            }

            return $config;
        }

        throw new LoadException("Config path does not exist: $path");
    }
}
