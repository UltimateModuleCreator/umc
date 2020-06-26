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

use App\Umc\CoreBundle\Controller\DownloadController;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Twig\Environment;

class DownloadControllerTest extends TestCase
{
    /**
     * @var Module | MockObject
     */
    private $repository;
    /**
     * @var Pool | MockObject
     */
    private $pool;
    /**
     * @var Router | MockObject
     */
    private $router;
    /**
     * @var FlashBagInterface | MockObject
     */
    private $flashBag;
    /**
     * @var Session | MockObject
     */
    private $session;
    /**
     * @var DownloadController
     */
    private $download;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(Module::class);
        $this->pool = $this->createMock(Pool::class);
        $this->session = $this->createMock(Session::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->session->method('getFlashBag')->willReturn($this->flashBag);
        $this->router = $this->createMock(Router::class);
        $this->download = new DownloadController($this->repository, $this->pool);
        $container = $this->createMock(ContainerInterface::class);
        /** @var ContainerInterface | MockObject $container */
        $container->method('has')->willReturnMap([
            ['session', true],
            ['router', true]
        ]);
        $container->method('get')->willReturnMap([
            ['session', $this->session],
            ['router', $this->router]
        ]);
        $this->download->setContainer($container);
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\DownloadController::run
     * @covers \App\Umc\CoreBundle\Controller\DownloadController::__construct
     */
    public function testRun()
    {
        $platform = $this->createMock(Platform::class);
        $version = $this->createMock(Platform\Version::class);
        $platform->method('getVersion')->willReturn($version);
        $this->repository->method('getRoot')->willReturn(__DIR__ . '/../_fixtures');
        $this->pool->method('getPlatform')->willReturn($platform);
        $this->flashBag->expects($this->never())->method('add');
        $this->router->expects($this->never())->method('generate');
        $this->assertInstanceOf(
            BinaryFileResponse::class,
            $this->download->run('platform', 'version', 'download')
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\DownloadController::run
     * @covers \App\Umc\CoreBundle\Controller\DownloadController::__construct
     */
    public function testRunWithException()
    {
        $this->pool->method('getPlatform')->willThrowException(new \Exception());
        $this->repository->expects($this->never())->method('load');
        $this->flashBag->expects($this->once())->method('add');
        $this->router->expects($this->once())->method('generate')->willReturn('url');
        $this->assertInstanceOf(RedirectResponse::class, $this->download->run('platform', 'version', 'dummy'));
    }
}
