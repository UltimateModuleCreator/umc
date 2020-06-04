<?php
declare(strict_types=1);

namespace App\Umc\CoreBundle\Tests\Unit\Service\Generator\Pool;

use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Service\Generator\Pool;
use App\Umc\CoreBundle\Service\Generator\Pool\Locator;
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
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool\Locator::getGeneratorPool
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool\Locator::__construct
     */
    public function testGetGeneratorPool()
    {
        $pool = $this->createMock(Pool::class);
        $this->version->method('getGeneratorPoolServiceId')->willReturn('generator.service');
        $this->serviceLocator->expects($this->once())
            ->method('getService')->with('generator.service')->willReturn($pool);
        $this->assertEquals($pool, $this->locator->getGeneratorPool($this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool\Locator::getGeneratorPool
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool\Locator::__construct
     */
    public function testGetGeneratorPoolWithError()
    {
        $this->version->method('getGeneratorPoolServiceId')->willReturn('generator.service');
        $this->serviceLocator->expects($this->once())
            ->method('getService')->with('generator.service')->willReturn('dummy');
        $this->expectException(\InvalidArgumentException::class);
        $this->locator->getGeneratorPool($this->version);
    }
}
