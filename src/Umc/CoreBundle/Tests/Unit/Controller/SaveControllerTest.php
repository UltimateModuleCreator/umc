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

use App\Umc\CoreBundle\Controller\SaveController;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Service\Builder;
use App\Umc\CoreBundle\Service\Validator\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SaveControllerTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Pool | MockObject
     */
    private $platformPool;
    /**
     * @var Builder | MockObject
     */
    private $builder;
    /**
     * @var Platform | MockObject
     */
    private $platform;
    /**
     * @var Platform\Version | MockObject
     */
    private $version;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var SaveController
     */
    private $saveController;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn($this->request);
        $this->platformPool = $this->createMock(Pool::class);
        $this->builder = $this->createMock(Builder::class);
        $this->module = $this->createMock(Module::class);
        $this->platform = $this->createMock(Platform::class);
        $this->version = $this->createMock(Platform\Version::class);
        $this->saveController = new SaveController(
            $requestStack,
            $this->platformPool,
            $this->builder
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveController::__construct
     */
    public function testRun()
    {
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->module->method('getExtensionName')->willReturn('module');
        $this->request->method('get')->willReturn(['data']);
        $this->builder->method('build')->willReturn($this->module);
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveController::__construct
     */
    public function testRunWithValidationException()
    {
        $this->platformPool->method('getPlatform')->willReturn($this->platform);
        $this->platform->method('getVersion')->willReturn($this->version);
        $this->module->method('getExtensionName')->willReturn('module');
        $this->request->method('get')->willReturn(['data']);
        $this->builder->method('build')->willThrowException($this->createMock(ValidationException::class));
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform', 'version'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Controller\SaveController::run
     * @covers \App\Umc\CoreBundle\Controller\SaveController::__construct
     */
    public function testRunWithGeneralException()
    {
        $this->platformPool->method('getPlatform')->willThrowException(new \Exception());
        $this->assertInstanceOf(JsonResponse::class, $this->saveController->run('platform', 'version'));
    }
}
