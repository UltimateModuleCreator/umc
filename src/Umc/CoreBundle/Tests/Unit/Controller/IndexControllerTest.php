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

use App\Umc\CoreBundle\Controller\IndexController;
use App\Umc\CoreBundle\Model\Platform;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class IndexControllerTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Controller\IndexController::run
     * @covers \App\Umc\CoreBundle\Controller\IndexController::__construct
     */
    public function testRun()
    {
        $response = $this->createMock(Response::class);
        $container = $this->createMock(ContainerInterface::class);
        $twig = $this->createMock(Environment::class);
        /** @var ContainerInterface | MockObject $container */
        $container->method('has')->willReturnMap([
            ['twig', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $twig]
        ]);
        $platforms = [
            $this->createMock(Platform::class),
            $this->createMock(Platform::class),
        ];
        $pool = $this->createMock(Platform\Pool::class);
        $pool->method('getPlatforms')->willReturn($platforms);
        $twig->expects($this->once())
            ->method('render')
            ->with('@UmcCore/index.html.twig', ['platforms' => $platforms])
            ->willReturn($response);
        $indexController = new IndexController($pool);
        $indexController->setContainer($container);
        $indexController->run();
    }
}
