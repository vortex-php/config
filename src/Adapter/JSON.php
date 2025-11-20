<?php

declare(strict_types=1);

namespace Vortex\Config\Adapter;

use Vortex\Config\Adapter;
use Vortex\Config\Exceptions\ParseException;

class JSON extends Adapter
{
    public function parse(string $contents): array
    {
        $config = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParseException('Config file is not a valid JSON file.');
        }

        return $config;
    }
}
