<?php

/**
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

namespace App\Umc\CoreBundle\Tests\Unit\Config;

use App\Umc\CoreBundle\Config\FileLoader;
use PHPUnit\Framework\TestCase;

class FileLoaderTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\FileLoader::load
     */
    public function testLoad()
    {
        $loader = new FileLoader();
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
     * @covers \App\Umc\CoreBundle\Config\FileLoader::load
     */
    public function testLoadMissingFile()
    {
        $loader = new FileLoader();
        $this->expectException(\Exception::class);
        $loader->load(__DIR__ . '/../_fixtures/yaml/missing.yml');
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\FileLoader::load
     */
    public function testLoadFileNotValid()
    {
        $loader = new FileLoader();
        $this->expectException(\Exception::class);
        $loader->load(__DIR__ . '/../_fixtures/yaml/not-valid.yml');
    }
}
