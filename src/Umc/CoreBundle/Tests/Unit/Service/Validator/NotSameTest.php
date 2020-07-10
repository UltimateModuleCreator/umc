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

use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Service\Validator\NotSame;
use PHPUnit\Framework\TestCase;

class NotSameTest extends TestCase
{
    /**
     * @var NotSame
     */
    private $validator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->validator = new NotSame(
            ['getLabelSingular', 'getLabelPlural'],
            'Label Singular and plural should be different for %s',
            'getLabelSingular'
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\NotSame::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\NotSame::__construct
     */
    public function testValidateWithError()
    {

        $entity = $this->createMock(Entity::class);
        $entity->method('getLabelSingular')->willReturn('value');
        $entity->method('getLabelPlural')->willReturn('value');
        $this->assertEquals(
            ['Label Singular and plural should be different for value'],
            $this->validator->validate($entity)
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\NotSame::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\NotSame::__construct
     */
    public function testValidate()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getLabelSingular')->willReturn('value1');
        $entity->method('getLabelPlural')->willReturn('value2');
        $this->assertEquals([], $this->validator->validate($entity));
    }
}
