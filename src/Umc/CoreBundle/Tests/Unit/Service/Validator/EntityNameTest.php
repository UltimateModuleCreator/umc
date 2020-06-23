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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Validator;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Service\Validator\EntityName;
use App\Umc\CoreBundle\Model\Entity;
use PHPUnit\Framework\TestCase;

class EntityNameTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\EntityName::validate
     */
    public function testValidate()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getNameAttribute')->willReturn($this->createMock(Attribute::class));
        $this->assertEquals([], (new EntityName())->validate($entity));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\EntityName::validate
     */
    public function testValidateWithNoNameAttribute()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getNameAttribute')->willReturn(null);
        $this->assertEquals(1, count((new EntityName())->validate($entity)));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\EntityName::validate
     */
    public function testValidateWithNoEntity()
    {
        $this->expectException(\InvalidArgumentException::class);
        (new EntityName())->validate(new \stdClass());
    }
}
