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

namespace App\Tests\Model;

use App\Model\Settings;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    /**
     * @var YamlLoader | MockObject
     */
    private $loader;
    /**
     * @var Settings
     */
    private $settings;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->loader = $this->createMock(YamlLoader::class);
        $this->settings = new Settings('file', 'path', $this->loader);
    }

    /**
     * @covers \App\Model\Settings::__construct
     * @covers \App\Model\Settings::setSettings
     * @covers \App\Model\Settings::getSettings
     */
    public function testGetSettings()
    {
        $initial = ['initialSettings'];
        $loaded = ['loadedConfig'];
        $this->loader->method('load')->willReturn($loaded);
        $this->settings->setSettings($initial);
        $this->assertEquals($initial, $this->settings->getSettings());
        $this->assertEquals($loaded, $this->settings->getSettings(true));
    }

    /**
     * @covers \App\Model\Settings::getSettings
     */
    public function testGetSettingsWithException()
    {
        $this->loader->expects($this->once())->method('load')->willThrowException(new \Exception());
        $this->assertEquals([], $this->settings->getSettings());
    }

    /**
     * @covers \App\Model\Settings::getPath
     */
    public function testGetPath()
    {
        $this->assertEquals('path', $this->settings->getPath());
    }

    /**
     * @covers \App\Model\Settings::getFile
     */
    public function testGetFile()
    {
        $this->assertEquals('file', $this->settings->getFile());
    }

    /**
     * @covers \App\Model\Settings::getSettingsAsYml
     */
    public function testGetSettingsAsYml()
    {
        $this->loader->expects($this->once())->method('arrayToYaml')->willReturn('yaml');
        $this->assertEquals('yaml', $this->settings->getSettingsAsYml());
    }
}
