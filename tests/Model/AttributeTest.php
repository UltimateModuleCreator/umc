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

namespace App\Tests\Model;

use App\Model\Attribute;
use App\Model\Entity;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var Attribute\TypeFactory
     */
    private $typeFactory;
    /**
     * @var array
     */
    private $data = [
        'label' => 'Attribute',
        'code' => 'attribute',
        'dummy' => 'dummy'
    ];

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->typeFactory = $this->createMock(Attribute\TypeFactory::class);
        $this->attribute = new Attribute($this->typeFactory, $this->data);
    }

    /**
     * @covers \App\Model\Attribute::getData()
     */
    public function testGetData()
    {
        $this->assertEquals('Attribute', $this->attribute->getData('label'));
        $this->assertEquals('attribute', $this->attribute->getData('code'));
        $this->assertNull($this->attribute->getData('non_existent'));
        $this->assertEquals('default', $this->attribute->getData('non_existent', 'default'));
    }

    /**
     * @covers \App\Model\Attribute::getRawData()
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->data, $this->attribute->getRawData());
    }

    /**
     * @covers \App\Model\Attribute::getPropertiesData()
     */
    public function testGetPropertiesData()
    {
        $propertiesData = $this->attribute->getPropertiesData();
        $this->assertArrayHasKey('label', $propertiesData);
        $this->assertArrayHasKey('code', $propertiesData);
        $this->assertArrayNotHasKey('dummy', $propertiesData);
    }

    /**
     * @covers \App\Model\Attribute::toArray()
     */
    public function testToArray()
    {
        $entityArray = $this->attribute->toArray();
        $this->assertArrayHasKey('label', $entityArray);
        $this->assertArrayNotHasKey('dummy', $entityArray);
    }

    /**
     * @covers \App\Model\Attribute::__construct
     * @covers \App\Model\Attribute::getTypeInstance
     */
    public function testGetTypeInstance()
    {
        $typeInstance = $this->createMock(Attribute\TypeInterface::class);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        //test memoizing
        $attrTypeInstance = $this->attribute->getTypeInstance();
        $this->attribute->getTypeInstance();
        $this->assertEquals($typeInstance, $attrTypeInstance);
    }

    /**
     * @covers \App\Model\Attribute::getEntity
     * @covers \App\Model\Attribute::setEntity
     */
    public function testSetEntity()
    {
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        $this->attribute->setEntity($entity);
        $this->assertEquals($entity, $this->attribute->getEntity());
    }

    /**
     * @covers \App\Model\Attribute::getProcessedOptions
     * @covers \App\Model\Attribute::toConstantName
     */
    public function testGetProcessedOptions()
    {
        $options = "o1\no2\n3\n";
        $attribute = new Attribute($this->typeFactory, ['options' => $options]);
        $expected = [
            'O1' => [
                'value' => 1,
                'label' => 'o1'
            ],
            'O2' => [
                'value' => 2,
                'label' => 'o2'
            ],
            '_3' => [
                'value' => 3,
                'label' => '3'
            ],
            '_EMPTY' => [
                'value' => 4,
                'label' => ''
            ]
        ];
        $this->assertEquals($expected, $attribute->getProcessedOptions());
    }
}
