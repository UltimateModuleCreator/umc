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
namespace App\Tests\Service;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\Module;
use App\Service\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @covers \App\Service\Validator::validateAttribute()
     */
    public function testValidateAttribute()
    {
        $validator = new Validator();
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getData')->willReturn('name');
        $this->assertEquals(0, count($validator->validateAttribute($attribute)));
    }

    /**
     * @covers \App\Service\Validator::validateAttribute()
     */
    public function testValidateAttributeWithKeywordCode()
    {
        $validator = new Validator();
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getData')->willReturn('function');
        $this->assertEquals(1, count($validator->validateAttribute($attribute)));
    }
    /**
     * @covers \App\Service\Validator::validateAttribute()
     */
    public function testValidateAttributeWithRestrictedCode()
    {
        $validator = new Validator();
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getData')->willReturn('updated_at');
        $this->assertEquals(1, count($validator->validateAttribute($attribute)));
    }

    /**
     * @covers \App\Service\Validator::validateEntity()
     */
    public function testValidateEntity()
    {
        $validator = new Validator();
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        /** @var Attribute | MockObject $attribute */
        $entity->method('getData')->willReturn('some_name');
        $attribute = $this->createMock(Attribute::class);
        $entity->expects($this->once())->method('getAttributes')->willReturn([$attribute]);
        $entity->expects($this->once())->method('getNameAttribute')->willReturn($attribute);
        $attribute->method('getData')->willReturn('name');
        $this->assertEquals(0, count($validator->validateEntity($entity)));
    }

    /**
     * @covers \App\Service\Validator::validateEntity()
     */
    public function testValidateEntityWithKeywordCode()
    {
        $validator = new Validator();
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $entity->method('getData')->willReturn('endswitch');
        $entity->expects($this->once())->method('getAttributes')->willReturn([$attribute]);
        $entity->expects($this->once())->method('getNameAttribute')->willReturn($attribute);
        $attribute->method('getData')->willReturn('name');
        $this->assertEquals(1, count($validator->validateEntity($entity)));
    }

    /**
     * @covers \App\Service\Validator::validateEntity()
     */
    public function testValidateEntityWithMissingNameAttribute()
    {
        $validator = new Validator();
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $entity->method('getData')->willReturn('endswitch');
        $entity->expects($this->once())->method('getAttributes')->willReturn([$attribute]);
        $entity->expects($this->once())->method('getNameAttribute')->willReturn(null);
        $attribute->method('getData')->willReturn('name');
        $this->assertEquals(2, count($validator->validateEntity($entity)));
    }

    /**
     * @covers \App\Service\Validator::validate()
     */
    public function testValidate()
    {
        $validator = new Validator();
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->method('getData')->willReturn('some_name');
        $module->method('getEntities')->willReturn([]);
        $this->assertEquals(0, count($validator->validate($module)));
    }

    /**
     * @covers \App\Service\Validator::validate()
     */
    public function testValidateWithError()
    {
        $validator = new Validator();
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->method('getData')->willReturn('Magento');
        $module->method('getEntities')->willReturn([]);
        $this->assertEquals(1, count($validator->validate($module)));
    }

    /**
     * @covers \App\Service\Validator::validate()
     */
    public function testValidateWithEntityCollision()
    {
        $validator = new Validator();
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->method('getData')->willReturn('Namespace');
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getData')->willReturn('some_code');
        $entity1->method('getData')->willReturn('entityName');
        $entity1->method('getAttributes')->willReturn([$attribute]);
        $entity1->method('getNameAttribute')->willReturn($attribute);

        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getData')->willReturn('entityName');
        $entity2->method('getAttributes')->willReturn([]);
        $entity2->method('getNameAttribute')->willReturn(null);
        $module->method('getEntities')->willReturn([
            $entity1,
            $entity2
        ]);
        $this->assertEquals(2, count($validator->validate($module)));
    }
}
