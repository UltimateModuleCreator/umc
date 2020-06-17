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

use App\Umc\CoreBundle\Controller\ListController;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ListControllerTest extends TestCase
{
    /**
     * @var Pool | MockObject
     */
    private $pool;
    /**
     * @var Module | MockObject
     */
    private $repository;
    /**
     * @var MockObject|Environment
     */
    private $twig;
    /**
     * @var ListController
     */
    private $listController;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->pool = $this->createMock(Pool::class);
        $this->repository = $this->createMock(Module::class);
        $container = $this->createMock(ContainerInterface::class);
        $this->twig = $this->createMock(Environment::class);
        /** @var ContainerInterface | MockObject $container */
        $container->method('has')->willReturnMap([
            ['twig', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig]
        ]);
        $this->listController = new ListController(
            $this->pool,
            $this->repository
        );
        $this->listController->setContainer($container);
    }


    /**
     * @covers \App\Umc\CoreBundle\Controller\ListController::run
     * @covers \App\Umc\CoreBundle\Controller\ListController::getVersionGroups
     * @covers \App\Umc\CoreBundle\Controller\ListController::__construct
     */
    public function testRunForVersion()
    {
        $platform = $this->createMock(Platform::class);
        $version = $this->createMock(Platform\Version::class);
        $platform->method('getVersion')->willReturn($version);
        $this->repository->expects($this->once())->method('getVersionModules')->with($version)
            ->willReturn(['file1', 'file2']);
        $this->pool->expects($this->once())->method('getPlatform')->willReturn($platform);
        $expected = [
            'groups' => [
                [
                    'platform' => $platform,
                    'versions' => [
                        [
                            'version' => $version,
                            'files' => ['file1', 'file2']
                        ],
                    ]
                ]
            ]
        ];
        $response = $this->createMock(Response::class);
        $this->twig->expects($this->once())
            ->method('render')
            ->with('@UmcCore/list.html.twig', $expected)
            ->willReturn($response);
        $this->assertInstanceOf(Response::class, $this->listController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\ListController::run
     * @covers \App\Umc\CoreBundle\Controller\ListController::getPlatformGroups
     * @covers \App\Umc\CoreBundle\Controller\ListController::__construct
     */
    public function testRunForPlatform()
    {
        $platform = $this->createMock(Platform::class);
        $version1 = $this->createMock(Platform\Version::class);
        $version2 = $this->createMock(Platform\Version::class);
        $platform->method('getVersions')->willReturn([$version1, $version2]);
        $this->repository->expects($this->exactly(2))->method('getVersionModules')->willReturnMap([
            [$version1, ['file1']],
            [$version2, ['file2', 'file3']],
        ]);
        $this->pool->expects($this->once())->method('getPlatform')->willReturn($platform);
        $expected = [
            'groups' => [
                [
                    'platform' => $platform,
                    'versions' => [
                        [
                            'version' => $version1,
                            'files' => ['file1']
                        ],
                        [
                            'version' => $version2,
                            'files' => ['file2', 'file3']
                        ],
                    ]
                ]
            ]
        ];
        $response = $this->createMock(Response::class);
        $this->twig->expects($this->once())
            ->method('render')
            ->with('@UmcCore/list.html.twig', $expected)
            ->willReturn($response);
        $this->assertInstanceOf(Response::class, $this->listController->run('platform'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\ListController::run
     * @covers \App\Umc\CoreBundle\Controller\ListController::getPlatformGroups
     * @covers \App\Umc\CoreBundle\Controller\ListController::__construct
     */
    public function testRunAll()
    {
        $platform1 = $this->createMock(Platform::class);
        $platform2 = $this->createMock(Platform::class);
        $version1 = $this->createMock(Platform\Version::class);
        $version2 = $this->createMock(Platform\Version::class);
        $platform1->method('getVersions')->willReturn([$version1, $version2]);
        $version3 = $this->createMock(Platform\Version::class);
        $platform2->method('getVersions')->willReturn([$version3]);
        $this->repository->expects($this->exactly(3))->method('getVersionModules')->willReturnMap([
            [$version1, ['file1']],
            [$version2, ['file2', 'file3']],
            [$version3, ['file100']],
        ]);
        $this->pool->expects($this->once())->method('getPlatforms')->willReturn([$platform1, $platform2]);
        $expected = [
            'groups' => [
                [
                    'platform' => $platform1,
                    'versions' => [
                        [
                            'version' => $version1,
                            'files' => ['file1']
                        ],
                        [
                            'version' => $version2,
                            'files' => ['file2', 'file3']
                        ],
                    ]
                ],
                [
                    'platform' => $platform2,
                    'versions' => [
                        [
                            'version' => $version3,
                            'files' => ['file100']
                        ],
                    ]
                ]
            ]
        ];
        $response = $this->createMock(Response::class);
        $this->twig->expects($this->once())
            ->method('render')
            ->with('@UmcCore/list.html.twig', $expected)
            ->willReturn($response);
        $this->assertInstanceOf(Response::class, $this->listController->run());
    }
}
