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

use App\Twig\Menu;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class MenuTest extends TestCase
{
    /**
     * @covers \App\Twig\Menu::__construct()
     * @covers \App\Twig\Menu::getFunctions()
     */
    public function testGetFunctions()
    {
        /** @var \App\Service\Menu | MockObject $menuService */
        $menuService = $this->createMock(\App\Service\Menu::class);
        $menu = new Menu($menuService);
        $functions = $menu->getFunctions();
        $this->assertEquals(1, count($functions));
        $this->assertInstanceOf(TwigFunction::class, $functions[0]);
        $this->assertEquals($menuService, $functions[0]->getCallable()());
    }
}
