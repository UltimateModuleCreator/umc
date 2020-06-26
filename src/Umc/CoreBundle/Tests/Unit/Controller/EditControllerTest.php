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

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use App\Umc\CoreBundle\Controller\EditController;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Module;
use App\Umc\CoreBundle\Repository\Settings;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class EditControllerTest extends TestCase
{
    /**
     * @var Pool | MockObject
     */
    private $platformPool;
    /**
     * @var Module | MockObject
     */
    private $moduleRepository;
    /**
     * @var Settings| MockObject
     */
    private $settingsRepository;
    /**
     * @var Platform | MockObject
     */
    private $platform;
    /**
     * @var Platform\Version | MockObject
     */
    private $version;
    /**
     * @var MockObject|Environment
     */
    private $twig;
    /**
     * @var MockObject|Session
     */
    private $session;
    /**
     * @var MockObject|FlashBagInterface
     */
    private $flashBag;
    /**
     * @var EditController
     */
    private $editController;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->platformPool = $this->createMock(Pool::class);
        $formLoaderFactory = $this->createMock(PlatformAwareFactory::class);
        $uiModifier = $this->createMock(ModifierInterface::class);
        $this->moduleRepository = $this->createMock(Module::class);
        $this->settingsRepository = $this->createMock(Settings::class);
        $configLoader = $this->createMock(Loader::class);
        $configLoader->method('getConfig')->willReturn(['config']);
        $formLoaderFactory->method('createByVersion')->willReturn($configLoader);
        $this->platform = $this->createMock(Platform::class);
        $this->version = $this->createMock(Platform\Version::class);
        $this->session = $this->createMock(Session::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->session->method('getFlashBag')->willReturn($this->flashBag);
        $router = $this->createMock(Router::class);
        $this->editController = new EditController(
            $formLoaderFactory,
            $this->platformPool,
            $uiModifier,
            $this->moduleRepository,
            $this->settingsRepository
        );
        $container = $this->createMock(ContainerInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $uiModifier->method('modify')->willReturnArgument(0);
        /** @var ContainerInterface | MockObject $container */
        $container->method('has')->willReturnMap([
            ['twig', true],
            ['session', true],
            ['router', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig],
            ['session', $this->session],
            ['router', $router]
        ]);
        $router->method('generate')->willReturn('url');
        $this->editController->setContainer($container);
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\EditController::run
     * @covers \App\Umc\CoreBundle\Controller\EditController::getKoConfig
     * @covers \App\Umc\CoreBundle\Controller\EditController::loadModuleData
     * @covers \App\Umc\CoreBundle\Controller\EditController::__construct
     */
    public function testRun()
    {
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->moduleRepository->expects($this->once())->method('load')
            ->willReturn(['module' => ['module_name' => 'dummy']]);
        $this->settingsRepository->method('loadVersionConfig')->willReturn(['version_config']);
        $expected = [
            'formConfig' => ['config'],
            'platform' => $this->platform,
            'version' => $this->version,
            'koConfig' => [
                [
                    'panel' => [],
                    'fields' => [],
                    'children' => []
                ]
            ],
            'data' => ['module_name' => 'dummy'],
            'title' => 'dummy',
            'defaults' => ['version_config']
        ];
        $response = $this->createMock(Response::class);
        $this->twig->expects($this->once())
            ->method('render')
            ->with('@UmcCore/edit.html.twig', $expected)
            ->willReturn($response);
        $this->editController->run('platform', 'version', 'module');
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\EditController::run
     * @covers \App\Umc\CoreBundle\Controller\EditController::getKoConfig
     * @covers \App\Umc\CoreBundle\Controller\EditController::__construct
     */
    public function testRunWithoutModule()
    {
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->moduleRepository->expects($this->never())->method('load');
        $this->settingsRepository->method('loadVersionConfig')->willReturn(['version_config']);
        $expected = [
            'formConfig' => ['config'],
            'platform' => $this->platform,
            'version' => $this->version,
            'koConfig' => [
                [
                    'panel' => [],
                    'fields' => [],
                    'children' => []
                ]
            ],
            'data' => null,
            'title' => 'New Module',
            'defaults' => ['version_config']
        ];
        $response = $this->createMock(Response::class);
        $this->twig->expects($this->once())
            ->method('render')
            ->with('@UmcCore/edit.html.twig', $expected)
            ->willReturn($response);
        $this->editController->run('platform', 'version');
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\EditController::run
     * @covers \App\Umc\CoreBundle\Controller\EditController::__construct
     */
    public function testRunWithError()
    {
        $this->platformPool->method('getPlatform')->willThrowException(new \Exception());
        $this->flashBag->expects($this->once())->method('add');
        $this->twig->expects($this->never())->method('render');
        $this->assertInstanceOf(RedirectResponse::class, $this->editController->run('platform', 'version'));
    }
}
