<?php

declare(strict_types=1);

namespace Vortex\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Vortex\Config\Adapter;
use Vortex\Config\Adapter\Dotenv;
use Vortex\Config\Adapter\JSON;
use Vortex\Config\Adapter\PHP;
use Vortex\Config\Adapter\YAML;
use Vortex\Config\Config;
use Vortex\Config\Exceptions\LoadException;
use Vortex\Config\Exceptions\NotFoundException;

class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        Config::set('init', 'initValue');
    }

    public function testSetParam(): void
    {
        Config::set('key', 'value');

        $this->assertEquals('initValue', Config::get('init'));

        $this->assertEquals('value', Config::get('key'));
        $this->assertEquals('default', Config::get('key-none', 'default'));
        $this->assertEquals(null, Config::get('keys'));

        Config::withStrict();

        $this->expectException(NotFoundException::class);
        Config::get('key2');

        Config::withStrict(false);

        Config::set('key3', ['name' => 'demo', 'info' => ['user' => ['age' => 22]]]);

        $this->assertEquals(['name' => 'demo'], Config::get('key3'));
        $this->assertEquals('demo', Config::get('key3.name'));
        $this->assertEquals(22, Config::get('key3.info.user.age'));
        $this->assertEquals('default', Config::get('key3.info1', 'default'));
        $this->assertEquals('default', Config::get('key3.info.user1', 'default'));
        $this->assertEquals('default', Config::get('key3.info.user.x', 'default'));
    }

    /**
     * @return array<int, array{
     *     adapter: class-string,
     *     extension: string,
     *     init: bool,
     *     withDesc: bool,
     *     arrays: bool,
     *     objects: bool
     * }>
     */
    public static function provideAdapters(): array
    {
        return [
            [
                'adapter' => PHP::class,
                'extension' => 'php',
                'init' => true,
                'withDesc' => true,
                'arrays' => true,
                'objects' => true,
            ],
            [
                'adapter' => JSON::class,
                'extension' => 'json',
                'init' => false,
                'withDesc' => true,
                'arrays' => true,
                'objects' => true,
            ],
            [
                'adapter' => Dotenv::class,
                'extension' => 'env',
                'init' => true,
                'withDesc' => true,
                'arrays' => false,
                'objects' => false,
            ],
            [
                'adapter' => YAML::class,
                'extension' => 'yaml',
                'init' => true,
                'withDesc' => true,
                'arrays' => true,
                'objects' => true,
            ],
            [
                'adapter' => YAML::class,
                'extension' => 'yml',
                'init' => false,
                'withDesc' => true,
                'arrays' => true,
                'objects' => true,
            ],
        ];
    }

    #[DataProvider('provideAdapters')]
    public function testAdapters(
        string $adapter,
        string $extension,
        bool $init,
        bool $withDesc,
        bool $arrays,
        bool $objects
    ): void {
        $adapter = new $adapter;

        $key = $extension;

        if (! $adapter instanceof Adapter) {
            throw new Exception('Test includes invalid adapter.');
        }

        Config::load($key, __DIR__."/testdata/config.$extension", $adapter);

        $this->assertEquals('keyValue', Config::get("$key.key"));

        if ($init) {
            $this->assertEquals('initValue', Config::get("$key.init"));
        }

        if ($withDesc) {
            $this->assertEquals('keyWithDesc', Config::get("$key.keyWithDesc"));
        }

        if ($objects) {
            $this->assertEquals('nestedKeyValue', Config::get("$key.nested.key"));
        }

        if ($arrays) {
            $val = Config::get("$key.array");
            $this->assertCount(3, $val);
            $this->assertIsArray($val);
            foreach ($val as $k => $v) {
                $this->assertEquals("arrayValue$k", $v);
            }
        }

        $this->assertEquals('default', Config::get("$key.non-existing-key", 'default'));
        $this->assertEquals(null, Config::get("$key.non-existing-key1"));

        $this->expectException(LoadException::class);
        Config::load($key, __DIR__."/testdata/non-existing.$extension", $adapter);
    }

    public function testLoadDirectory(): void
    {
        Config::load('dir', __DIR__.'/testdata/config', new PHP);

        $this->assertEquals('test1_value', Config::get('dir.test1.test1_key'));
        $this->assertEquals('test2_value', Config::get('dir.test2.test2_key'));
    }
}
