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

namespace App\Tests\Service;

use App\Model\Module;
use App\Service\Generator;
use App\Service\Generator\GeneratorInterface;
use App\Service\Source\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class GeneratorTest extends TestCase
{
    /**
     * @var Reader | MockObject
     */
    private $reader;
    /**
     * @var GeneratorInterface[] | MockObject[]
     */
    private $contentGenerators;
    /**
     * @var GeneratorInterface | MockObject
     */
    private $moduleGenerator;
    /**
     * @var GeneratorInterface | MockObject
     */
    private $entityGenerator;
    /**
     * @var Generator
     */
    private $generator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->reader = $this->createMock(Reader::class);
        $this->moduleGenerator = $this->createMock(GeneratorInterface::class);
        $this->entityGenerator = $this->createMock(GeneratorInterface::class);
        $this->contentGenerators = [
            'module' => $this->moduleGenerator,
            'entity' => $this->entityGenerator
        ];

        $this->generator = new Generator($this->reader, $this->contentGenerators);
    }

    /**
     * @covers \App\Service\Generator::generateModule()
     * @covers \App\Service\Generator::__construct()
     * @covers \App\Service\Generator::getContentGenerator()
     */
    public function testGenerateModule()
    {
        $this->reader->expects($this->once())->method('getFiles')->willReturn([
            ['scope' => 'module'],
            ['scope' => 'entity'],
            []
        ]);
        $this->moduleGenerator->expects($this->exactly(2))
            ->method('generateContent')
            ->willReturnOnConsecutiveCalls(['module1' => 'content1'], ['module2' => 'content2']);
        $this->entityGenerator->expects($this->once())
            ->method('generateContent')
            ->willReturn(['entity1' => 'content3']);
        $expected = [
            'module1' => 'content1',
            'module2' => 'content2',
            'entity1' => 'content3',
        ];
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $this->assertEquals($expected, $this->generator->generateModule($module));
    }

    /**
     * @covers \App\Service\Generator::generateModule()
     * @covers \App\Service\Generator::getContentGenerator()
     */
    public function testGenerateModuleWithError()
    {
        $this->reader->expects($this->once())->method('getFiles')->willReturn([
            ['scope' => 'dummy'],
        ]);
        $this->expectException(\Exception::class);
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $this->generator->generateModule($module);
    }

    /**
     * @covers \App\Service\Generator::generateModule()
     * @covers \App\Service\Generator::getContentGenerator()
     */
    public function testGenerateModuleWithMissingGenerator()
    {
        $this->generator = new Generator($this->reader, ['module' => new \stdClass()]);
        $this->reader->method('getFiles')->willReturn(
            ['scope' => 'module']
        );
        $this->expectException(\Exception::class);
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $this->generator->generateModule($module);
    }
}
