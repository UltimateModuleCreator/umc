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

namespace App\Umc\CoreBundle\Tests\Unit\Repository;

use App\Umc\CoreBundle\Config\Provider;
use App\Umc\CoreBundle\Config\Provider\Factory;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Repository\Settings;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class SettingsTest extends TestCase
{
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var Factory | MockObject
     */
    private $providerFactory;
    /**
     * @var Platform | MockObject
     */
    private $platform;
    /**
     * @var Version | MockObject
     */
    private $version;
    /**
     * @var Settings
     */
    private $settings;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->providerFactory = $this->createMock(Factory::class);
        $this->platform = $this->createMock(Platform::class);
        $this->version = $this->createMock(Version::class);
        $this->version->method('getPlatform')->willReturn($this->platform);
        $this->settings = new Settings(
            $this->filesystem,
            $this->providerFactory,
            'root'
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::savePlatformConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::save
     * @covers \App\Umc\CoreBundle\Repository\Settings::getPlatformRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testSavePlatformConfig()
    {
        $this->filesystem->expects($this->once())->method('dumpFile');
        $this->settings->savePlatformConfig($this->platform, []);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::loadPlatformConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::load
     * @covers \App\Umc\CoreBundle\Repository\Settings::getPlatformRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testLoadPlatformConfig()
    {
        $provider = $this->createMock(Provider::class);
        $this->providerFactory->method('create')->willReturn($provider);
        $provider->method('getConfig')->willReturn(['config']);
        $this->filesystem->method('exists')->willReturn(true);
        $this->assertEquals(['config'], $this->settings->loadPlatformConfig($this->platform));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::loadPlatformConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::load
     * @covers \App\Umc\CoreBundle\Repository\Settings::getPlatformRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testLoadPlatformConfigMissingFile()
    {
        $this->providerFactory->expects($this->never())->method('create');
        $this->filesystem->method('exists')->willReturn(false);
        $this->assertEquals([], $this->settings->loadPlatformConfig($this->platform));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::deletePlatformConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::save
     * @covers \App\Umc\CoreBundle\Repository\Settings::getPlatformRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testDeletePlatformConfig()
    {
        $this->filesystem->method('exists')->willReturn(true);
        $this->filesystem->expects($this->once())->method('remove');
        $this->settings->deletePlatformConfig($this->platform);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::deletePlatformConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::save
     * @covers \App\Umc\CoreBundle\Repository\Settings::getPlatformRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testDeletePlatformConfigMissingFile()
    {
        $this->filesystem->method('exists')->willReturn(false);
        $this->filesystem->expects($this->never())->method('remove');
        $this->settings->deletePlatformConfig($this->platform);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::loadVersionConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::getVersionRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::load
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testLoadVersionConfig()
    {
        $provider = $this->createMock(Provider::class);
        $this->providerFactory->method('create')->willReturn($provider);
        $provider->method('getConfig')->willReturn(['config']);
        $this->filesystem->method('exists')->willReturn(true);
        $this->assertEquals(['config'], $this->settings->loadVersionConfig($this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::loadVersionConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::getVersionRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::load
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testLoadVersionConfigMissingFile()
    {
        $this->providerFactory->expects($this->never())->method('create');
        $this->filesystem->method('exists')->willReturn(false);
        $this->expectException(Settings\MissingSettingsFileException::class);
        $this->settings->loadVersionConfig($this->version, false);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::loadVersionConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::getVersionRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::load
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testLoadVersionConfigMissingFileWithPlatform()
    {
        $provider = $this->createMock(Provider::class);
        $this->providerFactory->expects($this->once())->method('create')->willReturn($provider);
        $this->platform->method('getCode')->willReturn('platform');
        $this->version->method('getCode')->willReturn('version');
        $this->filesystem->method('exists')->willReturnMap([
            ['root/platform/default.yml', true],
            ['root/platform/version/default.yml', false]
        ]);
        $provider->method('getConfig')->willReturn(['config']);
        $this->assertEquals(['config'], $this->settings->loadVersionConfig($this->version, true));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::saveVersionConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::save
     * @covers \App\Umc\CoreBundle\Repository\Settings::getVersionRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testSaveVersionConfig()
    {
        $this->filesystem->expects($this->once())->method('dumpFile');
        $this->settings->saveVersionConfig($this->version, []);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::deleteVersionConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::delete
     * @covers \App\Umc\CoreBundle\Repository\Settings::getVersionRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testDeleteVersionConfig()
    {
        $this->filesystem->method('exists')->willReturn(true);
        $this->filesystem->expects($this->once())->method('remove');
        $this->settings->deleteVersionConfig($this->version);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Settings::deleteVersionConfig
     * @covers \App\Umc\CoreBundle\Repository\Settings::delete
     * @covers \App\Umc\CoreBundle\Repository\Settings::getVersionRoot
     * @covers \App\Umc\CoreBundle\Repository\Settings::__construct
     */
    public function testDeleteVersionConfigMissingFile()
    {
        $this->filesystem->method('exists')->willReturn(false);
        $this->filesystem->expects($this->never())->method('remove');
        $this->settings->deleteVersionConfig($this->version);
    }
}
