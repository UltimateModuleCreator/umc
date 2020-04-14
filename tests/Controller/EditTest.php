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

namespace App\Tests\Controller;

use App\Controller\Edit;
use App\Model\Settings;
use App\Service\Form\Loader;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class EditTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var RequestStack | MockObject
     */
    private $requestStack;
    /**
     * @var YamlLoader | MockObject
     */
    private $yamlLoader;
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Settings | MockObject
     */
    private $settings;
    /**
     * @var Edit
     */
    private $edit;
    /**
     * @var Loader | MockObject
     */
    private $formLoader;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->twig->expects($this->once())->method('render')->with($this->equalTo('template'))
            ->willReturn('output');
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($this->request);
        $this->yamlLoader = $this->createMock(YamlLoader::class);
        $this->settings = $this->createMock(Settings::class);

        $this->formLoader = $this->createMock(Loader::class);

        $this->edit = new Edit(
            'template',
            $this->requestStack,
            $this->yamlLoader,
            $this->settings,
            $this->formLoader,
            'basePath'
        );
        /** @var ContainerInterface | MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['twig', true],
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig],
        ]);
        $this->edit->setContainer($container);
    }

    /**
     * @covers \App\Controller\Edit::run
     * @covers \App\Controller\Edit::getGroupedAttributeConfig
     * @covers \App\Controller\Edit::getUiConfig
     * @covers \App\Controller\Edit::__construct
     */
    public function testRunNewMode()
    {
        $this->request->method('get')->willReturn(null);
        $this->yamlLoader->expects($this->never())->method('load');
        $this->formLoader->method('getForms')->willReturn([
            [
                'rows' => [['data']]
            ],
            [
                'rows' => [['data']]
            ]
        ]);
        $this->edit->run();
    }

    /**
     * @covers \App\Controller\Edit::run
     * @covers \App\Controller\Edit::getGroupedAttributeConfig
     * @covers \App\Controller\Edit::getUiConfig
     * @covers \App\Controller\Edit::__construct
     */
    public function testEditValidData()
    {
        $this->request->method('get')->willReturn('module');
        $this->yamlLoader->expects($this->once())->method('load')->willReturn([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName'
        ]);
        $this->formLoader->method('getForms')->willReturn([
            'attribute' => [
                'rows' => [['data']]
            ],
            'entity' => [
                'rows' => [['data']]
            ]
        ]);
        $this->edit->run();
    }

    /**
     * @covers \App\Controller\Edit::run
     * @covers \App\Controller\Edit::getGroupedAttributeConfig
     * @covers \App\Controller\Edit::getUiConfig
     * @covers \App\Controller\Edit::__construct
     */
    public function testEditNotValidData()
    {
        $this->request->method('get')->willReturn('module');
        $this->yamlLoader->expects($this->once())->method('load')->willThrowException(new \Exception());
        $this->formLoader->method('getForms')->willReturn([
            [
                'rows' => [['data']]
            ],
            [
                'rows' => [['data']]
            ]
        ]);
        $this->edit->run();
    }
}
