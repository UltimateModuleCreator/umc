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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Cs;

use App\Umc\CoreBundle\Service\Cs\Executor;
use App\Umc\CoreBundle\Service\Cs\ProcessFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ExecutorTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Cs\Executor::run()
     * @covers \App\Umc\CoreBundle\Service\Cs\Executor::__construct()
     */
    public function testRun()
    {
        /** @var ProcessFactory | MockObject $factory */
        $factory = $this->createMock(ProcessFactory::class);
        $codingStandards = new Executor(
            $factory,
            'phpcsPath',
            '_cs/'
        );
        $factory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($this->getProcessMock(), $this->getProcessMock());
        $result = $codingStandards->run(['standard1', 'standard2'], 'folder');
        $this->assertArrayHasKey('_cs/standard1', $result);
        $this->assertArrayHasKey('_cs/standard2', $result);
    }

    /**
     * @return MockObject | Process
     */
    protected function getProcessMock()
    {
        $process = $this->createMock(Process::class);
        $process->expects($this->once())->method('run');
        $process->expects($this->once())->method('getOutput');
        return $process;
    }
}
