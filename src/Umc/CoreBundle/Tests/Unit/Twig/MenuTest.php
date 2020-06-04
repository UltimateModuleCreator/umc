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

namespace App\Umc\CoreBundle\Tests\Unit\Twig;

use App\Umc\CoreBundle\Service\Menu;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class MenuTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Twig\Menu::getFunctions
     * @covers \App\Umc\CoreBundle\Twig\Menu::__construct
     */
    public function testGetFunctions()
    {
        $menu = $this->createMock(Menu::class);
        $menu->expects($this->once())->method('getItems')->willReturn('items');
        $twigMenu = new \App\Umc\CoreBundle\Twig\Menu($menu);
        $result = $twigMenu->getFunctions();
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(TwigFunction::class, $result[0]);
        $this->assertEquals('items', $result[0]->getCallable()());
    }
}
