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

use App\Umc\CoreBundle\Service\Validator\CompositeValidator;
use App\Umc\CoreBundle\Service\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class CompositeValidatorTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\CompositeValidator::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\CompositeValidator::addValidator
     * @covers \App\Umc\CoreBundle\Service\Validator\CompositeValidator::__construct
     */
    public function testValidate()
    {
        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator2 = $this->createMock(ValidatorInterface::class);
        $object = new \stdClass();
        $validator1->method('validate')->willReturn(['err11', 'err12']);
        $validator2->method('validate')->willReturn(['err21', 'err22']);
        $expected = ['err11', 'err12', 'err21', 'err22'];
        $composite = new CompositeValidator([$validator1, $validator2]);
        $this->assertEquals($expected, $composite->validate($object));
    }
}
