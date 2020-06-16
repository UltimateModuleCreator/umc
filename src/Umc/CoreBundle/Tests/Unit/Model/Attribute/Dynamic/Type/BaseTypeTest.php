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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Dynamic\Type;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class BaseTypeTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Dynamic | MockObject
     */
    private $dynamic;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->dynamic = $this->createMock(Dynamic::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::getDynamic
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::__construct
     */
    public function testGetDynamic()
    {
        $this->assertEquals($this->dynamic, $this->getInstance([])->getDynamic());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::renderTemplate
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::__construct
     */
    public function testRenderTemplateNoTemplate()
    {
        $type = $this->getInstance([]);
        $this->dynamic->expects($this->never())->method('getAttribute');
        $this->assertEquals('', $type->renderTemplate('missing'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::renderTemplate
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::__construct
     */
    public function testRenderFormWithTemplate()
    {
        $type = $this->getInstance(['templates' => ['template' => 'value']]);
        $attribute = $this->createMock(Attribute::class);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->dynamic->expects($this->once())->method('getAttribute')->willReturn($attribute);
        $attribute->expects($this->once())->method('getEntity')->willReturn($entity);
        $entity->expects($this->once())->method('getModule')->willReturn($module);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $type->renderTemplate('template'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::getSourceModel
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::__construct
     */
    public function testGetSourceModel()
    {
        $instance = $this->getInstance(['source_model' => 'source']);
        $this->assertEquals('source', $instance->getSourceModel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::getFlags
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::__construct
     */
    public function testGetFlags()
    {
        $instance = $this->getInstance(['dynamic_flags' => ['flag1' => true, 'flag2' => false]]);
        $this->assertEquals(['flag1'], $instance->getFlags());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::hasFlag
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType::__construct
     */
    public function testHasFlags()
    {
        $instance = $this->getInstance(['dynamic_flags' => ['flag1' => true, 'flag2' => false]]);
        $this->assertTrue($instance->hasFlag('flag1'));
        $this->assertFalse($instance->hasFlag('flag2'));
        $this->assertFalse($instance->hasFlag('missing'));
    }

    /**
     * @param array $data
     * @return BaseType
     */
    private function getInstance(array $data): BaseType
    {
        return new BaseType($this->twig, $this->dynamic, $data);
    }
}
