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

namespace App\Tests\Service\License;

use App\Model\Module;
use App\Service\License\Php;
use App\Service\License\Replacer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PhpTest extends TestCase
{
    /**
     * @covers \App\Service\License\Php::process
     * @covers \App\Service\License\Php::__construct
     */
    public function testProcess()
    {
        /** @var MOdule | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->method('getData')->willReturn('string');
        /** @var Replacer | MockObject $replacer */
        $replacer = $this->createMock(Replacer::class);
        $license = "This is the license for/**/<!----> Namespace_ModuleName for the year 2018\n" .
                "and {{this should not be replaced}}";
        $replacer->method('replaceVars')->willReturn($license);
        $expected = PHP_EOL . "/**" . PHP_EOL;
        $expected .= " * This is the license for Namespace_ModuleName for the year 2018" . PHP_EOL;
        $expected .= " * and {{this should not be replaced}}" . PHP_EOL;
        $expected .= " */" . PHP_EOL;
        $php = new Php($replacer);
        $this->assertEquals($expected, $php->process($module));
    }

    /**
     * @covers \App\Service\License\Php::process
     * @covers \App\Service\License\Php::__construct
     */
    public function testProcessNoValue()
    {
        /** @var MOdule | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->method('getData')->willReturn('string');
        /** @var Replacer | MockObject $replacer */
        $replacer = $this->createMock(Replacer::class);
        $replacer->method('replaceVars')->willReturn('');
        $php = new Php($replacer);
        $this->assertEquals('', $php->process($module));
    }
}
