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
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetVersion()
    {

    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::getLatestVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testGetLatestVersion()
    {

    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::addVersion
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testAddVersion()
    {

    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform::isSupported
     * @covers \App\Umc\CoreBundle\Model\Platform::__construct
     */
    public function testIsSupported()
    {

    }
}
