<?php
declare(strict_types=1);

namespace App\Umc\Test\Unit\CoreBundle\Model;

use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PlatformTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getName
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetName()
    {
        $platform = new Platform('code', ['name' => 'Name'], []);
        $this->assertEquals('Name', $platform->getName());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getCode
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetCode()
    {
        $platform = new Platform('code', [], []);
        $this->assertEquals('code', $platform->getCode());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getDescription
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetDescription()
    {
        $platform = new Platform('code', ['description' => 'Description'], []);
        $this->assertEquals('Description', $platform->getDescription());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getImage
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetImage()
    {
        $platform = new Platform('code', ['image' => 'image'], []);
        $this->assertEquals('image', $platform->getImage());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getUrl
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetUrl()
    {
        $platform = new Platform('code', ['url' => 'url'], []);
        $this->assertEquals('url', $platform->getUrl());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getDestinationFolder
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetDestinationFolder()
    {
        $platform = new Platform('code', ['config' => ['destination' => 'destination']], []);
        $this->assertEquals('destination', $platform->getDestinationFolder());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getConfig
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
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
        $platform = new Platform('code', ['config' => $config], []);
        $this->assertEquals($config, $platform->getConfig());
        $this->assertEquals(['value1'], $platform->getConfig('key1'));
        $this->assertEquals(['sub1' => 'subval1', 'sub2' => 'subval2'], $platform->getConfig('key3'));
        $this->assertEquals(['subval1'], $platform->getConfig('key3.sub1'));
        $this->assertEquals([], $platform->getConfig('key3.dummy'));
        $this->assertEquals([], $platform->getConfig('dummy'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getVersions
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetVersions()
    {
        $version1 = $this->createMock(Version::class);
        $version1->method('getCode')->willReturn('v1');
        $version2 = $this->createMock(Version::class);
        $version2->method('getCode')->willReturn('v2');
        $platform = new Platform('code', [], [$version1, $version2]);
        $this->assertEquals(['v1' => $version1, 'v2' => $version2], $platform->getVersions());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::addVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::getLatestVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetVersion()
    {
        $version1 = $this->getVersionMock('v1');
        $version2 = $this->getVersionMock('v2');
        $platform = new Platform('code', [], [$version1, $version2]);
        $this->assertEquals($version1, $platform->getVersion('v1'));
        $this->assertEquals($version2, $platform->getVersion('v2'));
        $this->assertEquals($version1, $platform->getVersion());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::addVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetVersionWithMissingVersion()
    {
        $version = $this->getVersionMock('v1');
        $platform = new Platform('code', [], [$version]);
        $this->expectException(\InvalidArgumentException::class);
        $platform->getVersion('dummy');
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getLatestVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::addVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetLatestVersion()
    {
        $version1 = $this->getVersionMock('v1');
        $version2 = $this->getVersionMock('v2');
        $platform = new Platform('code', [], [$version1, $version2]);
        $this->assertEquals($version1, $platform->getLatestVersion());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getLatestVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::addVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetLatestVersionWithMissingVersions()
    {
        $platform = new Platform('code', [], []);
        $this->expectException(\InvalidArgumentException::class);
        $platform->getLatestVersion();
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::isSupported
     * @covers \App\Umc\CoreBundle\Model\Platform::addVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testIsSupported()
    {
        $platform = new Platform('code', [], [$this->getVersionMock('v1')]);
        $this->assertTrue($platform->isSupported());
        $platform = new Platform('code', [], []);
        $this->assertFalse($platform->isSupported());
    }

    /**
     * @param $code
     * @return Version|MockObject
     */
    private function getVersionMock($code)
    {
        $version = $this->createMock(Version::class);
        $version->method('getCode')->willReturn($code);
        return $version;
    }
}
