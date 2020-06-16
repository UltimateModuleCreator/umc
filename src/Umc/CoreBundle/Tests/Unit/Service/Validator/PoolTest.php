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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Validator;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Service\Validator\Pool;
use App\Umc\CoreBundle\Service\Validator\ValidatorInterface;
use App\Umc\MagentoBundle\Model\Entity;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\Pool::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\Pool::validateEntity
     * @covers \App\Umc\CoreBundle\Service\Validator\Pool::validateAttribute
     * @covers \App\Umc\CoreBundle\Service\Validator\Pool::__construct
     */
    public function testValidate()
    {
        $moduleValidator = $this->getValidatorMock(['module error']);
        $entityValidator = $this->getValidatorMock(['entity error']);
        $attributeValidator = $this->getValidatorMock(['attribute error']);
        $module = $this->createMock(Module::class);
        $entity = $this->createMock(Entity::class);
        $attribute1 = $this->createMock(Attribute::class);
        $attribute2 = $this->createMock(Attribute::class);
        $entity->method('getAttributes')->willReturn([$attribute1, $attribute2]);
        $module->method('getEntities')->willReturn([$entity]);
        $expected = [
            'module error',
            'entity error',
            'attribute error',
            'attribute error',
        ];
        $pool = new Pool([
            'module' => $moduleValidator,
            'entity' => $entityValidator,
            'attribute' => $attributeValidator
        ]);
        $this->assertEquals($expected, $pool->validate($module));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\Pool::__construct
     */
    public function testConstruct()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Pool(['dummy']);
    }

    /**
     * @param array $errors
     * @return ValidatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getValidatorMock(array $errors)
    {
        $mock = $this->createMock(ValidatorInterface::class);
        $mock->method('validate')->willReturn($errors);
        return $mock;
    }
}
