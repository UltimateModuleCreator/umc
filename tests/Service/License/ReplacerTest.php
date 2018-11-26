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
use App\Service\License\Replacer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReplacerTest extends TestCase
{
    /**
     * @covers \App\Service\License\Replacer::replaceVars
     */
    public function testReplaceVars()
    {
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->method('getData')->willReturnMap([
            ['namespace', null, 'ModuleNamespace'],
            ['module_name', null, 'ModuleName']
        ]);
        $replacer = new Replacer();
        $license = '';
        $this->assertEquals('', $replacer->replaceVars($license, $module));
        $license = '{{Namespace}} {{Module}} {{Y}}';
        $this->assertEquals('ModuleNamespace ModuleName ' . date('Y'), $replacer->replaceVars($license, $module));
    }
}
