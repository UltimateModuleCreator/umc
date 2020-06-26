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

use App\Umc\CoreBundle\Controller\SaveSettingsController;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Repository\Settings;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SaveSettingsControllerTest extends TestCase
{
    /**
     * @var Platform\Pool | MockObject
     */
    private $pool;
    /**
     * @var Settings|MockObject
     */
    private $repository;
    /**
     * @var MockObject|RequestStack
     */
    private $requestStack;
    /**
     * @var MockObject|Request
     */
    private $request;
    /**
     * @var Platform|MockObject
     */
    private $platform;
    /**
     * @var Platform\Version|MockObject
     */
    private $version;
    /**
     * @var SaveSettingsController
     */
    private $saveController;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->pool = $this->createMock(Platform\Pool::class);
        $this->repository = $this->createMock(Settings::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')->willReturn($this->request);
        $this->platform = $this->createMock(Platform::class);
        $this->version = $this->createMock(Platform\Version::class);
        $this->saveController = new SaveSettingsController(
            $this->pool,
            $this->repository,
            $this->requestStack
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::__construct
     */
    public function testRunWithPlatformSave()
    {
        $this->pool->method('getPlatform')->willReturn($this->platform);
        $this->request->method('get')->willReturn(['data']);
        $this->platform->expects($this->never())->method('getVersion');
        $this->repository->expects($this->once())->method('savePlatformConfig');
        $this->repository->expects($this->never())->method('deletePlatformConfig');
        $this->repository->expects($this->never())->method('saveVersionConfig');
        $this->repository->expects($this->never())->method('deleteVersionConfig');
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::__construct
     */
    public function testRunWithPlatformDelete()
    {
        $this->pool->method('getPlatform')->willReturn($this->platform);
        $this->platform->expects($this->never())->method('getVersion');
        $this->request->method('get')->willReturn(['restore' => ['restore' => true]]);
        $this->repository->expects($this->never())->method('savePlatformConfig');
        $this->repository->expects($this->once())->method('deletePlatformConfig');
        $this->repository->expects($this->never())->method('saveVersionConfig');
        $this->repository->expects($this->never())->method('deleteVersionConfig');
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::__construct
     */
    public function testRunWithVersionSave()
    {
        $this->pool->method('getPlatform')->willReturn($this->platform);
        $this->platform->expects($this->once())->method('getVersion')->willReturn($this->version);
        $this->request->method('get')->willReturn(['data']);
        $this->repository->expects($this->never())->method('savePlatformConfig');
        $this->repository->expects($this->never())->method('deletePlatformConfig');
        $this->repository->expects($this->once())->method('saveVersionConfig');
        $this->repository->expects($this->never())->method('deleteVersionConfig');
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::__construct
     */
    public function testRunWithVersionDelete()
    {
        $this->pool->method('getPlatform')->willReturn($this->platform);
        $this->platform->expects($this->once())->method('getVersion')->willReturn($this->version);
        $this->request->method('get')->willReturn(['restore' => ['restore' => true]]);
        $this->repository->expects($this->never())->method('savePlatformConfig');
        $this->repository->expects($this->never())->method('deletePlatformConfig');
        $this->repository->expects($this->never())->method('saveVersionConfig');
        $this->repository->expects($this->once())->method('deleteVersionConfig');
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveSettingsController::__construct
     */
    public function testRunWithException()
    {
        $this->pool->method('getPlatform')->willThrowException(new \Exception());
        $this->repository->expects($this->never())->method('savePlatformConfig');
        $this->repository->expects($this->never())->method('deletePlatformConfig');
        $this->repository->expects($this->never())->method('saveVersionConfig');
        $this->repository->expects($this->never())->method('deleteVersionConfig');
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform', 'version'));
    }
}
