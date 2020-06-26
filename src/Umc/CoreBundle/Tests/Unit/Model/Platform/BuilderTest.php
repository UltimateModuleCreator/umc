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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Platform;

use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Builder;
use App\Umc\CoreBundle\Model\Platform\Factory;
use App\Umc\CoreBundle\Model\Platform\Version\Factory as VersionFactory;
use App\Umc\CoreBundle\Util\Sorter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    /**
     * @var Factory | MockObject
     */
    private $platformFactory;
    /**
     * @var VersionFactory | MockObject
     */
    private $versionFactory;
    /**
     * @var Sorter | MockObject
     */
    private $sorter;
    /**
     * @var Builder
     */
    private $builder;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->platformFactory = $this->createMock(Factory::class);
        $this->versionFactory = $this->createMock(VersionFactory::class);
        $this->sorter = $this->createMock(Sorter::class);
        $this->builder = new Builder(
            $this->platformFactory,
            $this->versionFactory,
            $this->sorter
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Builder::build
     * @covers \App\Umc\CoreBundle\Model\Platform\Builder::__construct
     */
    public function testBuild()
    {
        $this->versionFactory->expects($this->exactly(2))->method('create')
            ->willReturn($this->createMock(Platform\Version::class));
        $this->sorter->expects($this->once())->method('sort')->willReturnArgument(0);
        $platform = $this->createMock(Platform::class);
        $this->platformFactory->expects($this->once())->method('create')->willReturn($platform);
        $this->assertEquals($platform, $this->builder->build(['versions' => ['v1' => [], 'v2' => []]]));
    }
}
