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

namespace App\Umc\CoreBundle\Tests\Unit\Service\License;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Service\License\Processor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private $processor;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->processor = new Processor('code', 'prefix', 'suffix');
        $this->module = $this->createMock(Module::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\License\Processor::process
     * @covers \App\Umc\CoreBundle\Service\License\Processor::replaceVars
     * @covers \App\Umc\CoreBundle\Service\License\Processor::__construct
     */
    public function testProcess()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $license = "This is the license for/**/<!----> {{Namespace}}_{{Module}}\n" .
            "and {{this should not be replaced}}";
        $this->module->method('getLicense')->willReturn($license);

        $expected = 'prefix' . "/**" . PHP_EOL;
        $expected .= " * This is the license for Ns_Md" . PHP_EOL;
        $expected .= " * and {{this should not be replaced}}" . PHP_EOL;
        $expected .= " */" . 'suffix';
        $this->assertEquals($expected, $this->processor->process($this->module));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\License\Processor::process
     * @covers \App\Umc\CoreBundle\Service\License\Processor::__construct
     */
    public function testProcessNoValue()
    {
        $this->module->method('getLicense')->willReturn('');
        $this->assertEquals('', $this->processor->process($this->module));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\License\Processor::getCode
     * @covers \App\Umc\CoreBundle\Service\License\Processor::__construct
     */
    public function testGetCode()
    {
        $this->assertEquals('code', $this->processor->getCode());
    }
}
