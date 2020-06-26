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

namespace App\Umc\CoreBundle\Tests\Unit\Controller;

use App\Umc\CoreBundle\Controller\HelpController;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HelpControllerTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Controller\HelpController::run
     */
    public function testRun()
    {
        $twig = $this->createMock(Environment::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->with('twig')->willReturn(true);
        $container->method('get')->with('twig')->willReturn($twig);
        $twig->expects($this->once())->method('render')->willReturn('content');
        $help = new HelpController();
        $help->setContainer($container);
        $this->assertInstanceOf(Response::class, $help->run());
    }
}
