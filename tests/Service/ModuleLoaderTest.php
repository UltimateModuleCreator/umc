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

namespace App\Tests\Service;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\FactoryInterface;
use App\Model\Module;
use App\Service\ModuleLoader;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ModuleLoaderTest extends TestCase
{
    /**
     * @covers \App\Service\ModuleLoader::loadModule()
     * @covers \App\Service\ModuleLoader::getFactory()
     * @covers \App\Service\ModuleLoader::__construct()
     */
    public function testLoadModule()
    {
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->expects($this->exactly(2))->method('addEntity');
        $moduleFactory = $this->createMock(FactoryInterface::class);
        $moduleFactory->expects($this->once())->method('create')->willReturn($module);

        $entity1 = $this->createMock(Entity::class);
        $entity1->expects($this->once())->method('addAttribute');

        $entity2 = $this->createMock(Entity::class);
        $entity2->expects($this->once())->method('addAttribute');

        $entityFactory = $this->createMock(FactoryInterface::class);
        $entityFactory->expects($this->exactly(2))->method('create')->willReturnOnConsecutiveCalls($entity1, $entity2);

        $attr1 = $this->createMock(Attribute::class);

        $attr2 = $this->createMock(Attribute::class);

        $attrFactory = $this->createMock(FactoryInterface::class);
        $attrFactory->expects($this->exactly(2))->method('create')->willReturnOnConsecutiveCalls($attr1, $attr2);

        $data = [
            'namespace' => 'Namespace',
            '_entities' => [
                [
                    'name_singular' => 'Entity',
                    '_attributes' => [
                        [
                            'code' => 'code1'
                        ]
                    ]
                ],
                [
                    'name_singular' => 'Entity 2',
                    '_attributes' => [
                        [
                            'code' => 'code2'
                        ]
                    ]
                ]
            ]
        ];
        $loader = new ModuleLoader([
            'module' => $moduleFactory,
            'entity' => $entityFactory,
            'attribute' => $attrFactory
        ]);
        $loader->loadModule($data);
    }

    /**
     * @covers \App\Service\ModuleLoader::loadModule()
     * @covers \App\Service\ModuleLoader::getFactory()
     */
    public function testLoadModuleWithMissingFactory()
    {
        $loader = new ModuleLoader([]);
        $data = [
            'namespace' => 'Namespace'
        ];
        $this->expectException(\Exception::class);
        $loader->loadModule($data);
    }

    /**
     * @covers \App\Service\ModuleLoader::loadModule()
     * @covers \App\Service\ModuleLoader::getFactory()
     */
    public function testLoadModuleWithWrongFactory()
    {
        $loader = new ModuleLoader(['module' => new \stdClass()]);
        $data = [
            'namespace' => 'Namespace'
        ];
        $this->expectException(\Exception::class);
        $loader->loadModule($data);
    }
}
