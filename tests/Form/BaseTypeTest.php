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
namespace App\Tests\Form;

use App\Form\BaseType;
use App\Form\Field\TransformerInterface;
use App\Model\Settings;
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
     * @var Settings | MockObject
     */
    private $settings;
    /**
     * @var TransformerInterface | MockObject
     */
    private $checker;

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
                ],
                'sort' => true
            ],
            'type4' => [],
            'type5' => [
                'type' => 'type5',
                'choice_config' => [
                    1 => [
                        'label' => 'One',
                        'group' => 'one',
                    ],
                    2 => [
                        'label' => 'Two',
                        'group' => 'two'
                    ],
                    3 => []
                ],
                'group' => 'group'
            ],
        ];
        $this->loader = $this->createMock(YamlLoader::class);
        $this->checker = $this->createMock(TransformerInterface::class);
        $this->settings = $this->createMock(Settings::class);
        $this->baseType = new BaseType($this->loader, $this->checker, 'type', $this->settings, $typeMap);
        $this->formBuilder = $this->createMock(FormBuilderInterface::class);
    }

    /**
     * @covers \App\Form\BaseType::buildForm()
     * @covers \App\Form\BaseType::__construct()
     * @covers \App\Form\BaseType::getChoices()
     * @covers \App\Form\BaseType::getFields()
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
            'field5' => [
                'id' => 'field5',
                'options' => [],
                'type' => 'type5'
            ],
        ];
        $this->checker->method('transform')->willReturnArgument(0);
        $this->loader->expects($this->once())->method('load')->willReturn($fields);
        $this->formBuilder->expects($this->exactly(4))->method('add');
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
        $this->checker->method('transform')->willReturnArgument(0);
        $this->loader->expects($this->once())->method('load')->willReturn($fields);
        $this->formBuilder->expects($this->exactly(0))->method('add');
        $this->baseType->buildForm($this->formBuilder, []);
    }
}
