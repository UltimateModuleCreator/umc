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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Validator;

use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Service\Validator\Pool;
use App\Umc\CoreBundle\Service\Validator\Locator;
use App\Umc\CoreBundle\Service\Locator as ServiceLocator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocatorTest extends TestCase
{
    /**
     * @var ServiceLocator | MockObject
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
     * @covers \App\Umc\CoreBundle\Service\Validator\Locator::getValidatorPool
     * @covers \App\Umc\CoreBundle\Service\Validator\Locator::__construct
     */
    public function testGetGeneratorPool()
    {
        $pool = $this->createMock(Pool::class);
        $this->version->method('getValidatorPoolServiceId')->willReturn('validator.service');
        $this->serviceLocator->expects($this->once())
            ->method('getService')->with('validator.service')->willReturn($pool);
        $this->assertEquals($pool, $this->locator->getValidatorPool($this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\Locator::getValidatorPool
     * @covers \App\Umc\CoreBundle\Service\Validator\Locator::__construct
     */
    public function testGetGeneratorPoolWithError()
    {
        $this->version->method('getValidatorPoolServiceId')->willReturn('validator.service');
        $this->serviceLocator->expects($this->once())
            ->method('getService')->with('validator.service')->willReturn('dummy');
        $this->expectException(\InvalidArgumentException::class);
        $this->locator->getValidatorPool($this->version);
    }
}
