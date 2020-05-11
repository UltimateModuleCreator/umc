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

use App\Controller\SaveSettings;
use App\Model\Settings;
use App\Service\Writer;
use App\Service\WriterFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SaveSettingsTest extends TestCase
{
    /**
     * @var RequestStack | MockObject
     */
    private $requestStack;
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Writer | MockObject
     */
    private $writer;
    /**
     * @var WriterFactory | MockObject
     */
    private $writerFactory;
    /**
     * @var Settings | MockObject
     */
    private $settingsModel;
    /**
     * @var SaveSettings
     */
    private $save;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')->willReturn($this->request);
        $this->writer = $this->createMock(Writer::class);
        $this->writerFactory = $this->createMock(WriterFactory::class);
        $this->writerFactory->method('create')->willReturn($this->writer);
        $this->settingsModel = $this->createMock(Settings::class);
        $this->save = new SaveSettings($this->requestStack, $this->writerFactory, $this->settingsModel);
    }

    /**
     * @covers \App\Controller\SaveSettings::run
     * @covers \App\Controller\SaveSettings::__construct
     */
    public function testRun()
    {
        $this->request->expects($this->once())->method('get')->willReturn(['data']);
        $this->settingsModel->expects($this->once())->method('setSettings');
        $this->settingsModel->expects($this->once())->method('getSettingsAsYml');
        $this->writerFactory->expects($this->once())->method('create');
        $this->writer->expects($this->once())->method('writeFiles');
        $this->assertInstanceOf(JsonResponse::class, $this->save->run());
    }

    /**
     * @covers \App\Controller\SaveSettings::run
     * @covers \App\Controller\SaveSettings::__construct
     */
    public function testRunWithException()
    {
        $this->request->method('get')->willReturn([]);
        $this->writer->expects($this->once())->method('writeFiles')
            ->willThrowException(new \Exception());
        $this->assertInstanceOf(JsonResponse::class, $this->save->run());
    }
}
