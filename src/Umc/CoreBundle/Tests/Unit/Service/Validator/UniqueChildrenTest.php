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
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Service\Validator\UniqueChildren;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UniqueChildrenTest extends TestCase
{
    /**
     * @var Entity | MockObject
     */
    private $entity;
    /**
     * @var UniqueChildren
     */
    private $validator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->entity = $this->createMock(Entity::class);
        $this->validator = new UniqueChildren(
            'getAttributes',
            'getCode',
            'Entity %s has multiple attributes with code %s',
            'getLabelSingular'
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\UniqueChildren::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\UniqueChildren::__construct
     */
    public function testValidate()
    {
        $this->entity->method('getAttributes')->willReturn([
            $this->getAttributeMock('code1'),
            $this->getAttributeMock('code2'),
        ]);
        $this->assertEquals([], $this->validator->validate($this->entity));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\UniqueChildren::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\UniqueChildren::__construct
     */
    public function testValidateNoChildren()
    {
        $this->entity->method('getAttributes')->willReturn([]);
        $this->assertEquals([], $this->validator->validate($this->entity));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\UniqueChildren::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\UniqueChildren::__construct
     */
    public function testValidateNotUnique()
    {
        $this->entity->method('getAttributes')->willReturn([
            $this->getAttributeMock('code1'),
            $this->getAttributeMock('code2'),
            $this->getAttributeMock('code1'),
            $this->getAttributeMock('code2'),
            $this->getAttributeMock('code3'),
        ]);
        $this->entity->method('getLabelSingular')->willReturn('entity');
        $this->assertEquals(
            [
                'Entity entity has multiple attributes with code code1',
                'Entity entity has multiple attributes with code code2',
            ],
            $this->validator->validate($this->entity)
        );
    }

    /**
     * @param $code
     * @return Attribute|MockObject
     */
    private function getAttributeMock($code)
    {
        $mock = $this->createMock(Attribute::class);
        $mock->method('getCode')->willReturn($code);
        return $mock;
    }
}
