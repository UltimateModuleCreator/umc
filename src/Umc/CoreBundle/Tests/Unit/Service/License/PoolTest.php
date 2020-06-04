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

namespace App\Umc\CoreBundle\Tests\Unit\Service\License;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Service\License\Pool;
use App\Umc\CoreBundle\Service\License\Processor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\License\Pool::getProcessor
     * @covers \App\Umc\CoreBundle\Service\License\Pool::__construct
     */
    public function testGetProcessor()
    {
        $processor1 = $this->getProcessorMock('code1', 'value1');
        $processor2 = $this->getProcessorMock('code2', 'value2');
        $pool = new Pool([$processor1, $processor2]);
        $this->assertEquals($processor1, $pool->getProcessor('code1'));
        $this->assertEquals($processor2, $pool->getProcessor('code2'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\License\Pool::getProcessor
     * @covers \App\Umc\CoreBundle\Service\License\Pool::addProcessor
     * @covers \App\Umc\CoreBundle\Service\License\Pool::__construct
     */
    public function testGetProcessorWithException()
    {
        $processor = $this->getProcessorMock('code', 'value');
        $pool = new Pool([$processor]);
        $this->expectException(\InvalidArgumentException::class);
        $pool->getProcessor('missing');
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\License\Pool::process
     * @covers \App\Umc\CoreBundle\Service\License\Pool::addProcessor
     * @covers \App\Umc\CoreBundle\Service\License\Pool::__construct
     */
    public function testProcess()
    {
        $processor1 = $this->getProcessorMock('code1', 'value1');
        $processor2 = $this->getProcessorMock('code2', 'value2');
        $pool = new Pool([$processor1, $processor2]);
        $module = $this->createMock(Module::class);
        $this->assertEquals('value1', $pool->process($module, 'code1'));
        $this->assertEquals('value2', $pool->process($module, 'code2'));
    }

    /**
     * @param $code
     * @param $value
     * @return Processor|MockObject
     */
    private function getProcessorMock($code, $value)
    {
        $processor = $this->createMock(Processor::class);
        $processor->method('getCode')->willReturn($code);
        $processor->method('process')->willReturn($value);
        return $processor;
    }
}
