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

use App\Umc\CoreBundle\Service\Validator\RestrictedWords;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RestrictedWordsTest extends TestCase
{
    /**
     * @var RestrictedWords
     */
    private $restrictedWords;
    /**
     * @var MockObject
     */
    private $obj;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->restrictedWords = new RestrictedWords(
            'getSomething',
            ['restricted1', 'restricted2'],
            'error message'
        );
        $this->obj = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSomething'])
            ->getMock();
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\RestrictedWords::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\RestrictedWords::__construct
     */
    public function testValidate()
    {
        $this->obj->method('getSomething')->willReturn('restricted1');
        $this->assertEquals(['error message'], $this->restrictedWords->validate($this->obj));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\RestrictedWords::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\RestrictedWords::__construct
     */
    public function testValidateReservedWord()
    {
        $this->obj->method('getSomething')->willReturn('as');
        $this->assertEquals(
            ['as is a reserved PHP word and cannot be used'],
            $this->restrictedWords->validate($this->obj)
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\RestrictedWords::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\RestrictedWords::__construct
     */
    public function testValidateValid()
    {
        $this->obj->method('getSomething')->willReturn('dummy');
        $this->assertEquals([], $this->restrictedWords->validate($this->obj));
    }
}
