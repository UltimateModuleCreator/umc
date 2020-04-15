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

namespace App\Tests\ServiceForm\OptionProvider;

use App\Service\Form\OptionProvider\AdminMenu;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class AdminMenuTest extends TestCase
{
    /**
     * @var ContainerBagInterface | MockObject
     */
    private $containerBag;
    /**
     * @var AdminMenu
     */
    private $adminMenu;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->containerBag = $this->createMock(ContainerBagInterface::class);
        $this->adminMenu = new AdminMenu(
            $this->containerBag
        );
    }

    /**
     * @covers \App\Service\Form\OptionProvider\AdminMenu::getOptions
     * @covers \App\Service\Form\OptionProvider\AdminMenu::__construct
     */
    public function testGetOptions()
    {
        $this->containerBag->expects($this->once())->method('get')->with('menu_type_config')->willReturn(['data']);
        $this->assertEquals(['data'], $this->adminMenu->getOptions());
        //call twice to test memoizing
        $this->assertEquals(['data'], $this->adminMenu->getOptions());
    }
}
