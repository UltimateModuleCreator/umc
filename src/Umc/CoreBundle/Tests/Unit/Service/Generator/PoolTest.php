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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Generator;

use App\Umc\CoreBundle\Service\Generator\GeneratorInterface;
use App\Umc\CoreBundle\Service\Generator\Pool;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool::getGenerator
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool::__construct
     */
    public function testGetProcessor()
    {
        $generator1 = $this->createMock(GeneratorInterface::class);
        $generator2 = $this->createMock(GeneratorInterface::class);
        $pool = new Pool(['gen1' => $generator1, 'gen2' => $generator1]);
        $this->assertEquals($generator1, $pool->getGenerator('gen1'));
        $this->assertEquals($generator2, $pool->getGenerator('gen2'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool::getGenerator
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool::addGenerator
     * @covers \App\Umc\CoreBundle\Service\Generator\Pool::__construct
     */
    public function testGetProcessorWithException()
    {
        $generator = $this->createMock(GeneratorInterface::class);
        $pool = new Pool(['gen' => $generator]);
        $this->expectException(\InvalidArgumentException::class);
        $pool->getGenerator('missing');
    }
}
