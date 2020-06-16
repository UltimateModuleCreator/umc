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

use App\Umc\CoreBundle\Model\Entity as EntityModel;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Service\Generator\ContentProcessor;
use App\Umc\CoreBundle\Service\Generator\Entity;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class EntityTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var ContentProcessor
     */
    private $processor;
    /**
     * @var Entity
     */
    private $generator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->module = $this->createMock(Module::class);
        $this->processor = $this->createMock(ContentProcessor::class);
        $stringUtil = $this->createMock(StringUtil::class);
        $stringUtil->method('camel')->willReturnArgument(0);
        $this->generator = new Entity($this->twig, $stringUtil, $this->processor);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::processDestination
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::__construct
     */
    public function testGenerateContent()
    {
        $this->processor->method('process')->willReturnArgument(0);
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
                    'source' => 'path/_Entity_',
                    'destination' => 'path/_Entity_'
                ]
            )
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::processDestination
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::__construct
     * with empty generated content
     */
    public function testGenerateContentWithEmptyContent()
    {
        $this->processor->method('process')->willReturnArgument(0);
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
                    'source' => 'path/_Entity_',
                    'destination' => 'path/_Entity_'
                ]
            )
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::processDestination
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::__construct
     */
    public function testGenerateContentWithMissingSource()
    {
        $this->twig->expects($this->never())->method('render');
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generateContent($this->module, []);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::generateContent
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::processDestination
     * @covers \App\Umc\CoreBundle\Service\Generator\Entity::__construct
     */
    public function testGenerateContentWithMissingDestination()
    {
        $this->twig->expects($this->never())->method('render');
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generateContent($this->module, ['source' => 'source']);
    }

    /**
     * @param $nameSingular
     * @return MockObject | EntityModel
     */
    private function getEntityMock($nameSingular): MockObject
    {
        $entity = $this->createMock(EntityModel::class);
        $entity->method('getModule')->willReturn($this->module);
        $entity->method('getNameSingular')->willReturn($nameSingular);
        return $entity;
    }
}
