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

namespace App\Model;

class AttributeTypeSerialized extends AttributeType
{
    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        foreach ($this->getAttribute()->getSerialized() as $serialized) {
            if (in_array($serialized->getType(), ['product_attribute', 'product_attribute_multiselect'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        foreach ($this->getAttribute()->getSerialized() as $serialized) {
            if (in_array($serialized->getType(), ['product_attribute_set', 'product_attribute_set_multiselect'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     * TODO: check differently
     */
    public function isCanHaveOptions(): bool
    {
        foreach ($this->getAttribute()->getSerialized() as $serialized) {
            if (in_array($serialized->getType(), ['dropdown', 'multiselect'])) {
                return true;
            }
        }
        return false;
    }
}
