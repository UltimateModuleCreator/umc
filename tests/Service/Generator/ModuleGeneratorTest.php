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

namespace App\Tests\Service\Generator;

use App\Model\Module;
use App\Service\Generator\ModuleGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ModuleGeneratorTest extends TestCase
{
    /**
     * @var \Twig\Environment | MockObject
     */
    private $twig;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var ModuleGenerator
     */
    private $generator;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig\Environment::class);
        $this->module = $this->createMock(Module::class);
        $this->generator = new ModuleGenerator($this->twig);
    }

    /**
     * @covers \App\Service\Generator\ModuleGenerator::generateContent()
     * @covers \App\Service\Generator\ModuleGenerator::processDestination()
     * @covers \App\Service\Generator\ModuleGenerator::__construct()
     */
    public function testGenerateContent()
    {
        $this->twig->expects($this->once())->method('render')->willReturn('content');
        $expected = [
            'destination' => 'content'
        ];
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                [
                    'template' => 'template',
                    'destination' => 'destination'
                ]
            )
        );
    }

    /**
     * @covers \App\Service\Generator\ModuleGenerator::generateContent()
     *
     * with empty generated content
     */
    public function testGenerateContentWithEmptyContent()
    {
        $this->twig->expects($this->once())->method('render')->willReturn('  ');
        $this->assertEquals(
            [],
            $this->generator->generateContent(
                $this->module,
                [
                    'template' => 'template',
                    'destination' => 'destination'
                ]
            )
        );
    }
}
