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

namespace App\Umc\CoreBundle\Model\Attribute\Type;

class Serialized extends BaseType
{
    /**
     * @var bool
     */
    private $isProductAttribute;
    /**
     * @var bool
     */
    private $isProductAttributeSet;
    /**
     * @var array
     */
    private $processorsWithType;
    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        if ($this->isProductAttribute === null) {
            $this->isProductAttribute = false;
            foreach ($this->getAttribute()->getSerialized() as $serialized) {
                if ($serialized->getTypeInstance()->isProductAttribute()) {
                    $this->isProductAttribute = true;
                    break;
                }
            }
        }
        return $this->isProductAttribute;
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        if ($this->isProductAttributeSet === null) {
            $this->isProductAttributeSet = false;
            foreach ($this->getAttribute()->getSerialized() as $serialized) {
                if ($serialized->getTypeInstance()->isProductAttributeSet()) {
                    $this->isProductAttributeSet = true;
                    break;
                }
            }
        }
        return $this->isProductAttributeSet;
    }

    /**
     * @param $type
     * @return array
     */
//    public function getProcessorTypes($type): array
//    {
//        if ($this->processorsWithType === null) {
//            $this->processorsWithType = [];
//            $attribute = $this->getAttribute();
//            foreach ($attribute->getEntity()->getModule()->getProcessorTypes() as $processorType) {
//                $this->processorsWithType[$processorType] = array_reduce(
//                    $attribute->getSerialized(),
//                    function ($initial, \App\Model\Attribute\Serialized $serialized) use ($processorType) {
//                        return array_unique(
//                            array_merge($initial, $serialized->getProcessorTypes($processorType))
//                        );
//                    },
//                    []
//                );
//            }
//            $this->processorsWithType = array_merge($this->processorsWithType, parent::getProcessorTypes($type));
//        }
//        return $this->processorsWithType[$type] ?? [];
//    }
}
