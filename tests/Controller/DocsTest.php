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

use App\Controller\Docs;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class DocsTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
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
    }
    /**
     * @covers \App\Controller\Docs::run()
     * @covers \App\Controller\Docs::__construct()
     */
    public function testRun()
    {
        $this->twig->expects($this->once())->method('render')->with($this->equalTo($this->template));
        $docs = new Docs($this->template);
        /** @var ContainerInterface | MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['twig', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig]
        ]);
        $docs->setContainer($container);
        $docs->run();
    }
}
