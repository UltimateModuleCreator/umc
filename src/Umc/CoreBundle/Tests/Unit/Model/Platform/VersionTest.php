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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Platform;

use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    /**
     * @var Platform | MockObject
     */
    private $platform;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->platform = $this->createMock(Platform::class);
    }


    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getLabel
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetLabel()
    {
        $version = new Version('code', ['label' => 'label']);
        $this->assertEquals('label', $version->getLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getCode
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetCode()
    {
        $version = new Version('code', []);
        $this->assertEquals('code', $version->getCode());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getConfig
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetConfig()
    {
        $config = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => [
                'sub1' => 'subval1',
                'sub2' => 'subval2',
            ]
        ];
        $version = new Version('code', ['config' => $config]);
        $version->setPlatform($this->platform);
        $this->platform->method('getConfig')->willReturnMap([
            ['key4', ['value4']],
            ['key2', ['value2']],
            ['key3', []],
            ['key1', []],
            ['key3.sub1', []]
        ]);
        $this->assertEquals(['value1'], $version->getConfig('key1', true));
        $this->assertEquals(['value1'], $version->getConfig('key1', false));
        $this->assertEquals(['value2'], $version->getConfig('key2', true));
        $this->assertEquals(['value2'], $version->getConfig('key2', false));
        $this->assertEquals(['value2'], $version->getConfig('key2', true));
        $this->assertEquals(['subval1'], $version->getConfig('key3.sub1', true));
        $this->assertEquals(['value4'], $version->getConfig('key4', true));
        $this->assertEquals([], $version->getConfig('key4', false));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getConfig
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetConfigWithoutKey()
    {
        $config = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => [
                'sub1' => 'subval1',
                'sub2' => 'subval2',
            ]
        ];
        $version = new Version('code', ['config' => $config]);
        $version->setPlatform($this->platform);
        $this->platform->method('getConfig')->willReturn(['key4' => ['value4']]);
        $this->assertEquals($config, $version->getConfig(null, false));
        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => [
                'sub1' => 'subval1',
                'sub2' => 'subval2',
            ],
            'key4' => ['value4']
        ];
        $this->assertEquals($expected, $version->getConfig());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getModuleFactoryServiceId
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetModuleFactoryServiceId()
    {
        $version = new Version('code', ['config' => ['module_factory' => 'factory']]);
        $version->setPlatform($this->platform);
        $this->assertEquals('factory', $version->getModuleFactoryServiceId());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getModuleFactoryServiceId
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetModuleFactoryServiceIdWithMissingFactory()
    {
        $version = new Version('code', ['config' => []]);
        $version->setPlatform($this->platform);
        $this->expectException(Platform\ConfigException::class);
        $version->getModuleFactoryServiceId();
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getGeneratorPoolServiceId
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::setPlatform
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetGeneratorPoolServiceId()
    {
        $version = new Version('code', ['config' => ['generator_pool' => 'pool']]);
        $version->setPlatform($this->platform);
        $this->assertEquals('pool', $version->getGeneratorPoolServiceId());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getGeneratorPoolServiceId
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::setPlatform
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetGeneratorPoolServiceIdWithMissingPool()
    {
        $version = new Version('code', ['config' => []]);
        $version->setPlatform($this->platform);
        $this->expectException(Platform\ConfigException::class);
        $version->getGeneratorPoolServiceId();
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getCodingStandards
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetCodingStandards()
    {
        $version = new Version('code', ['config' => ['coding_standards' => ['coding']]]);
        $version->setPlatform($this->platform);
        $this->assertEquals(['coding'], $version->getCodingStandards());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::getPlatform
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::setPlatform
     * @covers \App\Umc\CoreBundle\Model\Platform\Version::__construct
     */
    public function testGetPlatform()
    {
        $version = new Version('code', ['config' => []]);
        $version->setPlatform($this->platform);
        $this->assertEquals($this->platform, $version->getPlatform());
    }
}
