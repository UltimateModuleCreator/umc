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

use App\Controller\Files;
use App\Service\ModuleList;
use App\Service\Source\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FilesTest extends TestCase
{
    /**
     * @var \Twig\Environment | MockObject
     */
    private $twig;
    /**
     * @var Reader | MockObject
     */
    private $reader;
    /**
     * @var string
     */
    private $template = 'template';

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig\Environment::class);
        $this->reader = $this->createMock(Reader::class);
    }

    /**
     * @covers \App\Controller\Files::run()
     * @covers \App\Controller\Files::__construct()
     */
    public function testRun()
    {
        $this->twig->expects($this->once())->method('render')->with($this->equalTo($this->template));
        $this->reader->expects($this->once())->method('getFiles')->willReturn([]);
        /** @var ModuleList | MockObject $moduleList */
        $files = new Files($this->reader, $this->template);
        /** @var ContainerInterface | MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['twig', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig]
        ]);
        $files->setContainer($container);
        $files->run();
    }
}
