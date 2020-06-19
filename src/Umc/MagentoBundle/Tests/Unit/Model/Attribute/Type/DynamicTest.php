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

namespace App\Umc\MagentoBundle\Tests\Unit\Model\Attribute\Type;

use App\Umc\MagentoBundle\Model\Attribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class DynamicTest extends TestCase
{
    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dynamic::getFlags
     */
    public function testGetFlags()
    {
        $twig = $this->createMock(Environment::class);
        $attribute = $this->createMock(\App\Umc\CoreBundle\Model\Attribute::class);
        $instance = new Attribute\Type\Dynamic($twig, $attribute, ['flags' => ['default']]);
        $attribute->method('getDynamic')->willReturn([
            $this->getFieldMock(['flag1', 'flag2']),
            $this->getFieldMock(['flag3']),
        ]);
        $result = $instance->getFlags();
        $this->assertTrue(in_array('default', $result));
        $this->assertTrue(in_array('flag1', $result));
        $this->assertTrue(in_array('flag2', $result));
        $this->assertTrue(in_array('flag3', $result));
    }

    /**
     * @param $flags
     * @return Attribute\Dynamic|MockObject
     */
    private function getFieldMock($flags)
    {
        $mock = $this->createMock(Attribute\Dynamic::class);
        $type = $this->createMock(Attribute\Dynamic\Type\BaseType::class);
        $type->method('getFlags')->willReturn($flags);
        $mock->method('getTypeInstance')->willReturn($type);
        return $mock;
    }
}
