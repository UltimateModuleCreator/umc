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
namespace App\Tests\Model\Attribute;

use App\Model\Attribute;
use App\Model\Entity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractTypeTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::__construct()
     * @covers \App\Model\Attribute\AbstractType::getAttribute()
     */
    public function testGetAttribute()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertEquals($attribute, $abstractType->getAttribute());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderGrid()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderGrid()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, ['grid_template' => 'tmpl.html.twig']);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderGrid());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderForm()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderFormGrid()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, ['form_template' => 'tmpl.html.twig']);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderForm());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderForm()
     * @covers \App\Model\Attribute\AbstractType::renderGrid()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderWrongType()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->twig->expects($this->never())->method('render');
        $this->assertEquals('', $abstractType->renderForm());
        $this->assertEquals('', $abstractType->renderGrid());
    }
}
