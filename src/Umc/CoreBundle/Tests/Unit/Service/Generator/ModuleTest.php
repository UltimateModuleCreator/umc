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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Generator;

use App\Umc\CoreBundle\Model\Module as ModuleModel;
use App\Umc\CoreBundle\Service\Generator\ContentProcessor;
use App\Umc\CoreBundle\Service\Generator\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class ModuleTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var ModuleModel | MockObject
     */
    private $module;
    /**
     * @var ContentProcessor | MockObject
     */
    private $processor;
    /**
     * @var Module
     */
    private $generator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->module = $this->createMock(ModuleModel::class);
        $this->processor = $this->createMock(ContentProcessor::class);
        $this->generator = new Module($this->twig, $this->processor);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::__construct
     */
    public function testGenerateContent()
    {
        $this->twig->expects($this->once())->method('render')->willReturn('content');
        $this->processor->method('process')->willReturnArgument(0);
        $expected = ['destination' => 'content'];
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                ['source' => 'source', 'destination' => 'destination']
            )
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::__construct
     */
    public function testGenerateContentWithEmptyContent()
    {
        $this->twig->expects($this->once())->method('render')->willReturn('  ');
        $this->processor->method('process')->willReturnArgument(0);
        $this->assertEquals(
            [],
            $this->generator->generateContent(
                $this->module,
                ['source' => 'source', 'destination' => 'destination']
            )
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::__construct
     */
    public function testGenerateContentWithMissingSource()
    {
        $this->twig->expects($this->never())->method('render');
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generateContent($this->module, []);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Module::__construct
     */
    public function testGenerateContentWithMissingDestination()
    {
        $this->twig->expects($this->never())->method('render');
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generateContent($this->module, ['source' => 'source']);
    }
}
