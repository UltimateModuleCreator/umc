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
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\Settings;
use App\Service\Form\Loader;
use App\Service\ModuleList;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class SettingsTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var string
     */
    private $template = 'template';
    /**
     * @var \App\Model\Settings | MockObject
     */
    private $settingsModel;
    /**
     * @var Loader | MockObject
     */
    private $formLoader;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->settingsModel = $this->createMock(\App\Model\Settings::class);
        $this->formLoader = $this->createMock(Loader::class);
    }

    /**
     * @covers \App\Controller\Settings::run()
     * @covers \App\Controller\Settings::__construct()
     */
    public function testRun()
    {
        $this->formLoader->method('getForms')->willReturn([
            [
                'rows' => [
                    [
                        'has_default' => true,
                        'dummy' => 'dummy'
                    ],
                    [
                        'dummy1' => 'dummy1'
                    ],
                ],
                [
                    [
                        'has_default' => true,
                        'dummy2' => 'dummy2'
                    ],
                    [
                        'dummy3' => 'dummy3'
                    ],
                ],
            ]
        ]);
        $this->twig->expects($this->once())->method('render')->with($this->equalTo($this->template))
            ->willReturn('output');
        $this->settingsModel->expects($this->once())->method('getSettings')->willReturn([]);
        /** @var ModuleList | MockObject $moduleList */
        $settings = new Settings($this->twig, $this->template, $this->settingsModel, $this->formLoader);
        /** @var ContainerInterface | MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['twig', true],
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig],
        ]);
        $settings->setContainer($container);
        $settings->run();
    }
}
