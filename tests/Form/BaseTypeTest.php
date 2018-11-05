<?php
declare(strict_types=1);
namespace App\Tests\Form;

use App\Form\BaseType;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;

class BaseTypeTest extends TestCase
{
    /**
     * @var YamlLoader | MockObject
     */
    private $loader;
    /**
     * @var BaseType
     */
    private $baseType;
    /**
     * @var FormBuilderInterface | MockObject
     */
    private $formBuilder;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $typeMap = [
            'type1' => [
                'type' => 'type1',
            ],
            'type2' => [
                'type' => 'type2',
            ],
            'type3' => [
                'type' => 'type3',
                'choice_config' => [
                    1 => [
                        'label' => 'One'
                    ],
                    2 => [
                        'label' => 'Two'
                    ],
                    3 => []
                ]
            ],
            'type4' => []
        ];
        $this->loader = $this->createMock(YamlLoader::class);
        $this->baseType = new BaseType($this->loader, 'type', $typeMap);
        $this->formBuilder = $this->createMock(FormBuilderInterface::class);
    }

    /**
     * @covers \App\Form\BaseType::buildForm()
     * @covers \App\Form\BaseType::__construct()
     */
    public function testBuildForm()
    {
        $fields = [
            'field1' => [
                'id' => 'field1',
                'options' => [],
                'type' => 'type1'
            ],
            'field2' => [
                'id' => 'field2',
                'options' => [],
                'type' => 'type2'
            ],
            'field3' => [
                'id' => 'field3',
                'options' => [],
                'type' => 'type3'
            ],
            'field4' => [
                'id' => 'field4',
                'options' => [],
                'type' => 'type4'
            ],
        ];
        $this->loader->expects($this->once())->method('load')->willReturn($fields);
        $this->formBuilder->expects($this->exactly(3))->method('add');
        $this->baseType->buildForm($this->formBuilder, []);
    }

    /**
     * @covers \App\Form\BaseType::buildForm
     * @covers \App\Form\BaseType::__construct()
     */
    public function testBuildFormWithException()
    {
        $fields = [
            'field1' => [
                'id' => 'field1',
                'type' => 'dummy',
                'options' => []
            ]
        ];
        $this->expectException(\Exception::class);
        $this->loader->expects($this->once())->method('load')->willReturn($fields);
        $this->formBuilder->expects($this->exactly(0))->method('add');
        $this->baseType->buildForm($this->formBuilder, []);
    }
}
