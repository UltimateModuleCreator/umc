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

namespace App\Service;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\FactoryInterface;
use App\Model\Module;

class ModuleLoader
{
    /**
     * @var FactoryInterface[]
     */
    private $factories;

    /**
     * ModuleLoader constructor.
     * @param FactoryInterface[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param array $data
     * @return \App\Model\AbstractModel | Module
     * @throws \Exception
     */
    public function loadModule(array $data)
    {
        /** @var Module $module */
        $module = $this->getFactory('module')->create($data);
        if (isset($data['_entities'])) {
            foreach ($data['_entities'] as $entityConfig) {
                /** @var Entity $entity */
                $entity = $this->getFactory('entity')->create($entityConfig);
                if (isset($entityConfig['_attributes'])) {
                    foreach ($entityConfig['_attributes'] as $attributeConfig) {
                        /** @var Attribute $attribute */
                        $attribute = $this->getFactory('attribute')->create($attributeConfig);
                        $entity->addAttribute($attribute);
                    }
                }
                $module->addEntity($entity);
            }
        }
        return $module;
    }

    /**
     * @param $key
     * @return FactoryInterface
     * @throws \Exception
     */
    private function getFactory($key)
    {
        if (!isset($this->factories[$key])) {
            throw new \Exception("Factory not found for {$key}");
        }
        if (!($this->factories[$key] instanceof FactoryInterface)) {
            throw new \Exception("Factory for {$key} does not implement " . FactoryInterface::class);
        }
        return $this->factories[$key];
    }
}
