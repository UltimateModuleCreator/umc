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

namespace App\Tests\Controller;

use App\Controller\Download;
use App\Controller\Files;
use App\Service\ModuleList;
use App\Service\Source\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;

class DownloadTest extends TestCase
{
    /**
     * @var RequestStack | MockObject
     */
    private $requestStack;
    /**
     * @var Reader | MockObject
     */
    private $request;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var Session | MockObject
     */
    private $session;
    /**
     * @var FlashBagInterface | MockObject
     */
    private $flashBag;
    /**
     * @var ContainerInterface | MockObject
     */
    private $container;
    /**
     * @var Router | MockObject
     */
    private $router;
    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')->willReturn($this->request);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->session = $this->createMock(Session::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->session->method('getFlashBag')->willReturn($this->flashBag);
        $this->router = $this->createMock(Router::class);
        /** @var ContainerInterface | MockObject $container */
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container->method('has')->willReturnMap([
            ['session', true],
            ['router', true]
        ]);
        $this->container->method('get')->willReturnMap([
            ['session', $this->session],
            ['router', $this->router]
        ]);
    }

    /**
     * @covers \App\Controller\Download::run()
     * @covers \App\Controller\Download::__construct()
     */
    public function testRun()
    {
        $this->request->expects($this->once())->method('get')->willReturn('download');
        $this->filesystem->expects($this->once())->method('exists')->willReturn(true);
        $download = new Download($this->requestStack, $this->filesystem, __DIR__ . '/../_fixtures');
        $download->setContainer($this->container);
        $this->assertInstanceOf(BinaryFileResponse::class, $download->run());
    }

    /**
     * @covers \App\Controller\Download::run()
     * @covers \App\Controller\Download::__construct()
     */
    public function testRunWithNoModule()
    {
        $this->request->expects($this->once())->method('get')->willReturn('');
        $this->flashBag->expects($this->once())->method('add');
        $this->router->expects($this->once())->method('generate')->willReturn('redirect');
        $download = new Download($this->requestStack, $this->filesystem, __DIR__ . '/../_fixtures');
        $download->setContainer($this->container);
        $this->assertInstanceOf(RedirectResponse::class, $download->run());
    }

    /**
     * @covers \App\Controller\Download::run()
     * @covers \App\Controller\Download::__construct()
     */
    public function testRunWithMissingFile()
    {
        $this->request->expects($this->once())->method('get')->willReturn('missing');
        $this->filesystem->method('exists')->willReturn(false);
        $this->flashBag->expects($this->once())->method('add');
        $this->router->expects($this->once())->method('generate')->willReturn('redirect');
        $download = new Download($this->requestStack, $this->filesystem, __DIR__ . '/../_fixtures');
        $download->setContainer($this->container);
        $this->assertInstanceOf(RedirectResponse::class, $download->run());
    }
}
