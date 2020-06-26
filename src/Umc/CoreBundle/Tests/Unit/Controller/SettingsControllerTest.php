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
use App\Umc\CoreBundle\Controller\SettingsController;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Settings;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Twig\Environment;

class SettingsControllerTest extends TestCase
{
    /**
     * @var PlatformAwareFactory | MockObject
     */
    private $formLoaderFactory;
    /**
     * @var
     */
    private $formLoader;
    /**
     * @var Pool
     */
    private $platformPool;
    /**
     * @var Settings
     */
    private $repository;
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Session | MockObject
     */
    private $session;
    /**
     * @var FlashBagInterface | MockObject
     */
    private $flashBag;
    /**
     * @var Router | MockObject
     */
    private $router;
    /**
     * @var Platform | MockObject
     */
    private $platform;
    /**
     * @var Platform\Version | MockObject
     */
    private $version;
    /**
     * @var SettingsController
     */
    private $settingsController;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->formLoaderFactory = $this->createMock(PlatformAwareFactory::class);
        $this->formLoader = $this->createMock(Loader::class);
        $this->platformPool = $this->createMock(Pool::class);
        $this->repository = $this->createMock(Settings::class);
        $this->settingsController = new SettingsController(
            $this->formLoaderFactory,
            $this->platformPool,
            $this->repository
        );
        $this->twig = $this->createMock(Environment::class);
        $this->session = $this->createMock(Session::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->router = $this->createMock(Router::class);
        $this->session->method('getFlashBag')->willReturn($this->flashBag);
        $container = $this->createMock(ContainerInterface::class);
        /** @var ContainerInterface | MockObject $container */
        $container->method('has')->willReturnMap([
            ['session', true],
            ['router', true],
            ['twig', true]
        ]);
        $container->method('get')->willReturnMap([
            ['session', $this->session],
            ['router', $this->router],
            ['twig', $this->twig]
        ]);
        $this->settingsController->setContainer($container);
        $this->platform = $this->createMock(Platform::class);
        $this->version = $this->createMock(Platform\Version::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::__construct
     */
    public function testRunWithPlatformAndVersionAndExistingVersionConfig()
    {
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->formLoaderFactory->expects($this->once())->method('createByVersion')->willReturn($this->formLoader);
        $this->formLoaderFactory->expects($this->never())->method('createByPlatform');
        $this->formLoader->method('getConfig')->willReturn(['config' => ['fields' => ['field1' => ['config']]]]);
        $this->version->method('getLabel')->willReturn('version');
        $this->platform->method('getName')->willReturn('platform');
        $this->repository->method('loadVersionConfig')->willReturn(['version_config' => 'version_config']);
        $this->repository->expects($this->never())->method('loadPlatformConfig');
        $this->router->method('generate')->willReturn('save');
        $expected = [
            'platform' => $this->platform,
            'version' => $this->version,
            'config' => ['config' => ['fields' => ['field1' => ['config']]]],
            'fields' => ['config' => ['field1'], 'restore' => ['restore']],
            'saveUrl' => 'save',
            'values' => [
                'version_config' => 'version_config',
                'restore' => ['restore' => false]
            ],
            'title' => "Default Settings for platform version version",
            'restore' => [
                'label' => 'Use platform default settings',
                'name' => 'restore.restore'
            ]
        ];
        $this->twig->expects($this->once())->method('render')->with('@UmcCore/settings.html.twig', $expected)
            ->willReturn('content');
        $this->assertInstanceOf(Response::class, $this->settingsController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::__construct
     */
    public function testRunWithPlatformAndVersionAndMissingVersionConfig()
    {
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->formLoaderFactory->expects($this->once())->method('createByVersion')->willReturn($this->formLoader);
        $this->formLoaderFactory->expects($this->never())->method('createByPlatform');
        $this->version->method('getLabel')->willReturn('version');
        $this->platform->method('getName')->willReturn('platform');
        $this->formLoader->method('getConfig')->willReturn(['config' => ['fields' => ['field1' => ['config']]]]);
        $this->repository->method('loadVersionConfig')
            ->willThrowException($this->createMock(Settings\MissingSettingsFileException::class));
        $this->repository->expects($this->once())->method('loadPlatformConfig')
            ->willReturn(['platform_config' => 'platform_config']);
        $this->router->method('generate')->willReturn('save');
        $expected = [
            'platform' => $this->platform,
            'version' => $this->version,
            'config' => ['config' => ['fields' => ['field1' => ['config']]]],
            'fields' => ['config' => ['field1'], 'restore' => ['restore']],
            'saveUrl' => 'save',
            'values' => [
                'platform_config' => 'platform_config',
                'restore' => ['restore' => true]
            ],
            'title' => "Default Settings for platform version version",
            'restore' => [
                'label' => 'Use platform default settings',
                'name' => 'restore.restore'
            ]
        ];
        $this->twig->expects($this->once())->method('render')->with('@UmcCore/settings.html.twig', $expected)
            ->willReturn('content');
        $this->assertInstanceOf(Response::class, $this->settingsController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::__construct
     */
    public function testRunWithPlatformWithoutVersion()
    {
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->formLoaderFactory->expects($this->never())->method('createByVersion');
        $this->formLoaderFactory->expects($this->once())->method('createByPlatform')->willReturn($this->formLoader);
        $this->version->method('getLabel')->willReturn('version');
        $this->platform->method('getName')->willReturn('platform');
        $this->formLoader->method('getConfig')->willReturn(['config' => ['fields' => ['field1' => ['config']]]]);
        $this->repository->expects($this->once())->method('loadPlatformConfig')
            ->willReturn(['platform_config' => 'platform_config']);
        $this->router->method('generate')->willReturn('save');
        $expected = [
            'platform' => $this->platform,
            'version' => $this->version,
            'config' => ['config' => ['fields' => ['field1' => ['config']]]],
            'fields' => ['config' => ['field1'], 'restore' => ['restore']],
            'saveUrl' => 'save',
            'values' => [
                'platform_config' => 'platform_config',
                'restore' => ['restore' => false]
            ],
            'title' => "Default Settings for platform",
            'restore' => [
                'label' => 'Delete platform settings',
                'name' => 'restore.restore'
            ]
        ];
        $this->twig->expects($this->once())->method('render')->with('@UmcCore/settings.html.twig', $expected)
            ->willReturn('content');
        $this->assertInstanceOf(Response::class, $this->settingsController->run('platform'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SettingsController::__construct
     */
    public function testRunWithException()
    {
        $this->platformPool->method('getPlatform')->willThrowException(new \Exception());
        $this->flashBag->expects($this->once())->method('add');
        $this->router->method('generate')->willReturn('save');
        $this->assertInstanceOf(RedirectResponse::class, $this->settingsController->run('platform'));
    }
}
