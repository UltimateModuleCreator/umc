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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Module\Factory;

use App\Umc\CoreBundle\Model\Module\Factory;
use App\Umc\CoreBundle\Model\Module\Factory\Locator;
use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Service\Locator as ServiceLocator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocatorTest extends TestCase
{
    /**
     * @var Locator | MockObject
     */
    private $serviceLocator;
    /**
     * @var Version | MockObject
     */
    private $version;
    /**
     * @var Locator
     */
    private $locator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->serviceLocator = $this->createMock(ServiceLocator::class);
        $this->version = $this->createMock(Version::class);
        $this->locator = new Locator(
            $this->serviceLocator
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module\Factory\Locator::getFactory
     * @covers \App\Umc\CoreBundle\Model\Module\Factory\Locator::__construct
     */
    public function testGetFactory()
    {
        $factory = $this->createMock(Factory::class);
        $this->version->expects($this->once())->method('getModuleFactoryServiceId')->willReturn('service');
        $this->serviceLocator->expects($this->once())->method('getService')->with('service')
            ->willReturn($factory);
        $this->assertEquals($factory, $this->locator->getFactory($this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module\Factory\Locator::getFactory
     * @covers \App\Umc\CoreBundle\Model\Module\Factory\Locator::__construct
     */
    public function testGetFactoryWithWrongType()
    {
        $factory = new \stdClass();
        $this->version->expects($this->once())->method('getModuleFactoryServiceId')->willReturn('service');
        $this->serviceLocator->expects($this->once())->method('getService')->with('service')
            ->willReturn($factory);
        $this->expectException(\InvalidArgumentException::class);
        $this->locator->getFactory($this->version);
    }
}
