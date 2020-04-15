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

use App\Controller\Index;
use App\Service\ModuleList;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class IndexTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var ModuleList | MockObject
     */
    private $moduleList;
    /**
     * @var string
     */
    private $template = 'template';

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->moduleList = $this->createMock(ModuleList::class);
    }

    /**
     * @covers \App\Controller\Index::run()
     * @covers \App\Controller\Index::__construct()
     */
    public function testRun()
    {
        $this->twig->expects($this->once())->method('render')->with($this->equalTo($this->template))
            ->willReturn('output');
        $this->moduleList->expects($this->once())->method('getModules')->willReturn([]);
        /** @var ModuleList | MockObject $moduleList */
        $index = new Index($this->moduleList, $this->template);
        /** @var ContainerInterface | MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['twig', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig]
        ]);
        $index->setContainer($container);
        $index->run();
    }
}
