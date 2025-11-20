<?php

// Comment line

use Vortex\Config\Config;

return [
    'key' => 'keyValue',
    'keyWithDesc' => 'keyWithDesc',
    'nested' => ['key' => 'nestedKeyValue'],
    'array' => ['arrayValue0', 'arrayValue1', 'arrayValue2'],
    'init' => Config::get('init'),
];
