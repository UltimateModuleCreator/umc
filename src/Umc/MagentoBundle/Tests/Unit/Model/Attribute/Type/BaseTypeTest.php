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

class BaseTypeTest extends TestCase
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
        $this->attribute = $this->createMock(Attribute::class);
    }

    public function testGetSourceModel()
    {
        $this->attribute->method('getOptionSourceVirtualType')->willReturn('attributeSourceModel');
        $this->assertEquals('source_model', $this->getInstance(['source_model' => 'source_model'])->getSourceModel());
        $this->assertEquals('attributeSourceModel', $this->getInstance([])->getSourceModel());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\BaseType::getAttributeColumnSettingsStringXml
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetAttributeColumnSettingsStringXml()
    {
        $this->attribute->expects($this->once())->method('isRequired')->willReturn(true);
        $expected = 'attributes nullable="false"';
        $this->assertEquals(
            $expected,
            $this->getInstance(['schema_attributes' => 'attributes'])->getAttributeColumnSettingsStringXml()
        );
    }

    /**
     * @param $data
     * @return BaseType
     */
    private function getInstance($data): BaseType
    {
        return new BaseType($this->twig, $this->attribute, $data);
    }
}
