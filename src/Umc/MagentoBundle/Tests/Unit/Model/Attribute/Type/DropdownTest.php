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
use App\Umc\MagentoBundle\Model\Attribute\Type\BaseType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class DropdownTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(\App\Umc\CoreBundle\Model\Attribute::class);
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dropdown::getSchemaType
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaType()
    {
        $this->attribute->method('areOptionsNumerical')->willReturn(true);
        $this->assertEquals('schema_type', $this->getInstance(['schema_type' => 'schema_type'])->getSchemaType());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dropdown::getSchemaType
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaTypeNonNumericalOptions()
    {
        $this->attribute->method('areOptionsNumerical')->willReturn(false);
        $this->assertEquals('varchar', $this->getInstance(['schema_type' => 'schema_type'])->getSchemaType());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dropdown::getSchemaAttributes
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaAttributes()
    {
        $this->attribute->method('areOptionsNumerical')->willReturn(true);
        $this->assertEquals(
            'schema_attributes',
            $this->getInstance(['schema_attributes' => 'schema_attributes'])->getSchemaAttributes()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\BaseType::getSchemaAttributes
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetSchemaAttributesNonNumericalOptions()
    {
        $this->attribute->method('areOptionsNumerical')->willReturn(false);
        $this->assertEquals(
            ' length="255"',
            $this->getInstance(['schema_attributes' => 'schema_attributes'])->getSchemaAttributes()
        );
    }

    /**
     * @param $data
     * @return BaseType
     */
    private function getInstance($data): Attribute\Type\Dropdown
    {
        return new Attribute\Type\Dropdown($this->twig, $this->attribute, $data);
    }
}
