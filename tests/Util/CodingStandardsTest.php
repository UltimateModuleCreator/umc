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
namespace App\Tests\Util;

use App\Util\CodingStandards;
use App\Util\ProcessFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CodingStandardsTest extends TestCase
{
    /**
     * @covers \App\Util\CodingStandards::run()
     * @covers \App\Util\CodingStandards::__construct()
     */
    public function testRun()
    {
        /** @var ProcessFactory | MockObject $factory */
        $factory = $this->createMock(ProcessFactory::class);
        $codingStandards = new CodingStandards(
            $factory,
            [
                'standard1',
                'standard2',
            ],
            'phpcsPath',
            'basePath'
        );
        $factory->method('create')->willReturnOnConsecutiveCalls($this->getProcessMock(), $this->getProcessMock());
        $result = $codingStandards->run();
        $this->assertArrayHasKey(CodingStandards::RESULT_KEY_PREFIX . 'standard1', $result);
        $this->assertArrayHasKey(CodingStandards::RESULT_KEY_PREFIX . 'standard2', $result);
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
