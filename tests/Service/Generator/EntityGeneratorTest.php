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

use App\Model\Entity;
use App\Model\Module;
use App\Service\Generator\EntityGenerator;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EntityGeneratorTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var EntityGenerator
     */
    private $generator;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->module = $this->createMock(Module::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->generator = new EntityGenerator($this->twig, $this->stringUtil);
    }

    /**
     * @covers \App\Service\Generator\EntityGenerator::generateContent
     * @covers \App\Service\Generator\EntityGenerator::processDestination
     * @covers \App\Service\Generator\EntityGenerator::processFileConfig
     * @covers \App\Service\Generator\EntityGenerator::__construct
     */
    public function testGenerateContent()
    {
        $this->twig->expects($this->exactly(2))->method('render')->willReturn('content');
        $this->module->expects($this->once())->method('getEntities')->willReturn(
            [
                $this->getEntityMock('firstEntity'),
                $this->getEntityMock('secondEntity')
            ]
        );
        $expected = [
            'path/FirstEntity' => 'content',
            'path/SecondEntity' => 'content'
        ];
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                [
                    'template' => 'template',
                    'source' => 'path/_Entity_'
                ]
            )
        );
    }

    /**
     * @covers \App\Service\Generator\EntityGenerator::generateContent
     * @covers \App\Service\Generator\EntityGenerator::processDestination
     * @covers \App\Service\Generator\EntityGenerator::processFileConfig
     * @covers \App\Service\Generator\EntityGenerator::__construct
     * with empty generated content
     */
    public function testGenerateContentWithEmptyContent()
    {
        $this->twig->expects($this->exactly(2))->method('render')->willReturnOnConsecutiveCalls('content', '  ');
        $expected = [
            'path/FirstEntity' => 'content',
        ];
        $this->module->expects($this->once())->method('getEntities')->willReturn(
            [
                $this->getEntityMock('firstEntity'),
                $this->getEntityMock('secondEntity')
            ]
        );
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                [
                    'scope' => 'entity',
                    'source' => 'path/_Entity_'
                ]
            )
        );
    }

    /**
     * @covers \App\Service\Generator\EntityGenerator::generateContent
     * @covers \App\Service\Generator\EntityGenerator::processDestination
     * @covers \App\Service\Generator\EntityGenerator::processFileConfig
     * @covers \App\Service\Generator\EntityGenerator::__construct
     */
    public function testGenerateContentWithMissingSource()
    {
        $this->twig->expects($this->never())->method('render');
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generateContent($this->module, []);
    }

    /**
     * @param $nameSingular
     * @return MockObject | Entity
     */
    private function getEntityMock($nameSingular): MockObject
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getModule')->willReturn($this->module);
        $entity->method('getNameSingular')->willReturn($nameSingular);
        return $entity;
    }
}
