<?php

declare(strict_types=1);

namespace Vortex\Config\Adapter;

use Vortex\Config\Adapter;
use Vortex\Config\Exceptions\ParseException;

class YAML extends Adapter
{
    public function parse(string $contents): array
    {
        $config = yaml_parse($contents);

        if ($config === false) {
            throw new ParseException('Config file is not a valid YAML file.');
        }

        return $config;
    }
}
