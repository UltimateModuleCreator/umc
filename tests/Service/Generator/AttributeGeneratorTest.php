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

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\Module;
use App\Service\Generator\AttributeGenerator;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class AttributeGeneratorTest extends TestCase
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
     * @var AttributeGenerator
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
        $this->twig = $this->createMock(\Twig\Environment::class);
        $this->module = $this->createMock(Module::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->generator = new AttributeGenerator($this->twig, $this->stringUtil);
        $this->stringUtil->method('camel')->willReturnArgument(0);

        $this->module->expects($this->once())->method('getEntities')->willReturn(
            [
                $this->getEntityMock('firstEntity', ['code1', 'code2']),
                $this->getEntityMock('secondEntity', ['code3'])
            ]
        );
    }

    /**
     * @covers \App\Service\Generator\AttributeGenerator::generateContent()
     * @covers \App\Service\Generator\AttributeGenerator::processDestination()
     * @covers \App\Service\Generator\AttributeGenerator::__construct()
     */
    public function testGenerateContent()
    {
        $this->twig->expects($this->exactly(3))->method('render')->willReturn('content');
        $expected = [
            'path/FirstEntity/Code1' => 'content',
            'path/FirstEntity/Code2' => 'content',
            'path/SecondEntity/Code3' => 'content'
        ];
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                [
                    'template' => 'template',
                    'destination' => 'path/_Entity_/_Code_'
                ]
            )
        );
    }

    /**
     * @covers \App\Service\Generator\AttributeGenerator::generateContent()
     * @covers \App\Service\Generator\AttributeGenerator::processDestination()
     * @covers \App\Service\Generator\AttributeGenerator::__construct()
     */
    public function testGenerateContentWithImage()
    {
        $this->twig->expects($this->exactly(3))->method('render')->willReturn(base64_encode('content'));
        $expected = [
            'path/FirstEntity/Code1' => 'content',
            'path/FirstEntity/Code2' => 'content',
            'path/SecondEntity/Code3' => 'content'
        ];
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                [
                    'template' => 'template',
                    'destination' => 'path/_Entity_/_Code_',
                    'is_image' => true
                ]
            )
        );
    }

    /**
     * @covers \App\Service\Generator\AttributeGenerator::generateContent()
     * @covers \App\Service\Generator\AttributeGenerator::processDestination()
     * @covers \App\Service\Generator\AttributeGenerator::__construct()
     * with empty generated content
     */
    public function testGenerateContentWithEmptyContent()
    {
        $this->twig->expects($this->exactly(3))->method('render')
            ->willReturnOnConsecutiveCalls('content', '  ', 'content');
        $expected = [
            'path/FirstEntity/Code1' => 'content',
            'path/SecondEntity/Code3' => 'content',
        ];
        $this->assertEquals(
            $expected,
            $this->generator->generateContent(
                $this->module,
                [
                    'template' => 'template',
                    'destination' => 'path/_Entity_/_Code_'
                ]
            )
        );
    }

    /**
     * @param $nameSingular
     * @return MockObject | Entity
     */
    private function getEntityMock($nameSingular, $attributeCodes)
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getNameSingular')->willReturn($nameSingular);
        $self = $this;
        $attributes = array_map(
            function ($item) use ($self, $entity) {
                return $self->getAttributeMock($item, $entity);
            },
            $attributeCodes
        );
        $entity->method('getAttributes')->willReturn($attributes);
        return $entity;
    }

    /**
     * @param $code
     * @param MockObject $entity
     * @return MockObject | Attribute
     */
    private function getAttributeMock($code, $entity)
    {
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getCode')->willReturn($code);
        $attribute->method('getEntity')->willReturn($entity);
        return $attribute;
    }
}
