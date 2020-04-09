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
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        foreach ($this->getAttribute()->getSerialized() as $serialized) {
            if ($serialized->getTypeInstance()->isProductAttribute()) {
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
            if ($serialized->getTypeInstance()->isProductAttributeSet()) {
                return true;
            }
        }
        return false;
    }
}
