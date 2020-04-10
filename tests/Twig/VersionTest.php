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
namespace App\Tests\Twig;

use App\Twig\Version;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class VersionTest extends TestCase
{
    /**
     * @covers \App\Twig\Version::getFunctions()
     */
    public function testGetFunctions()
    {
        $version = new Version();
        $functions = $version->getFunctions();
        $this->assertEquals(1, count($functions));
        $this->assertInstanceOf(TwigFunction::class, $functions[0]);
        $this->assertStringStartsWith('4.', $functions[0]->getCallable()());
    }
}
