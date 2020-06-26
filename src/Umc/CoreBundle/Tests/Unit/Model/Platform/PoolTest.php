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

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Builder;
use App\Umc\CoreBundle\Model\Platform\Pool;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    /**
     * @var Loader | MockObject
     */
    private $configLoader;
    /**
     * @var Builder | MockObject
     */
    private $builder;
    /**
     * @var Pool
     */
    private $pool;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->configLoader = $this->createMock(Loader::class);
        $this->builder = $this->createMock(Builder::class);
        $this->pool = new Pool(
            $this->configLoader,
            $this->builder
        );
    }


    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Pool::getPlatforms
     * @covers \App\Umc\CoreBundle\Model\Platform\Pool::__construct
     */
    public function testGetPlatforms()
    {
        $this->configLoader->method('getConfig')->willReturn([
            'code1' => ['data1'],
            'code2' => ['data1']
        ]);
        $platform1 = $this->createMock(Platform::class);
        $platform2 = $this->createMock(Platform::class);
        $this->builder->expects($this->exactly(2))->method('build')
            ->willReturnOnConsecutiveCalls($platform1, $platform2);
        $expected = [
            'code1' => $platform1,
            'code2' => $platform2
        ];
        $this->assertEquals($expected, $this->pool->getPlatforms());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->pool->getPlatforms());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Pool::getPlatform
     * @covers \App\Umc\CoreBundle\Model\Platform\Pool::__construct
     */
    public function testGetPlatform()
    {
        $this->configLoader->method('getConfig')->willReturn([
            'code' => ['data'],
        ]);

        $platform = $this->createMock(Platform::class);
        $this->builder->expects($this->once())->method('build')->willReturn($platform);
        $this->assertEquals($platform, $this->pool->getPlatform('code'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Platform\Pool::getPlatform
     * @covers \App\Umc\CoreBundle\Model\Platform\Pool::__construct
     */
    public function testGetPlatformWithMissingPlatform()
    {
        $this->configLoader->method('getConfig')->willReturn([
            'code' => ['data'],
        ]);

        $platform = $this->createMock(Platform::class);
        $this->builder->expects($this->once())->method('build')->willReturn($platform);
        $this->expectException(\InvalidArgumentException::class);
        $this->pool->getPlatform('missing');
    }
}
