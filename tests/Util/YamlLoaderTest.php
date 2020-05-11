<?php

/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */

declare(strict_types=1);

namespace App\Tests\Util;

use App\Util\YamlLoader;
use PHPUnit\Framework\TestCase;

class YamlLoaderTest extends TestCase
{
    /**
     * @covers \App\Util\YamlLoader::load()
     */
    public function testLoad()
    {
        $loader = new YamlLoader();
        $expected = [
            'dummy1' => [
                'var1' => 'val1',
                'var2' => 'val2',
            ],
            'dummy2' => 'val3'
        ];
        $this->assertEquals($expected, $loader->load(__DIR__ . '/../_fixtures/yaml/valid.yml'));
    }

    /**
     * @covers \App\Util\YamlLoader::load()
     */
    public function testLoadWithMissingFile()
    {
        $loader = new YamlLoader();
        $this->expectException(\Exception::class);
        $loader->load(__DIR__ . '/../_fixtures/yaml/missing.yml');
    }

    /**
     * @covers \App\Util\YamlLoader::load()
     */
    public function testLoadWithNotValidFile()
    {
        $loader = new YamlLoader();
        $this->expectException(\Exception::class);
        $loader->load(__DIR__ . '/../_fixtures/yaml/not-valid.yml');
    }

    /**
     * @covers \App\Util\YamlLoader::arrayToYaml
     */
    public function testArrayToYaml()
    {
        $array = [
            'dummy1' => [
                'var1' => 'val1',
                'var2' => 'val2',
            ],
            'dummy2' => 'val3'
        ];
        $loader = new YamlLoader();
        $expected = "dummy1:\n";
        $expected .= "  var1: val1\n";
        $expected .= "  var2: val2\n";
        $expected .= "dummy2: val3\n";
        $this->assertEquals($expected, $loader->arrayToYaml($array));
    }
}
