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

namespace App\Model\Attribute\Type;

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
}
