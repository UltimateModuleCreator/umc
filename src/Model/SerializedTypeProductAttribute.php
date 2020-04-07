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

class SerializedTypeProductAttribute extends SerializedType
{
    /**
     * @return string
     */
    public function getSourceModel(): string
    {
        $module = $this->getSerialized()->getAttribute()->getEntity()->getModule();
        $parts = [
            $module->getUmcCrudNamespace(),
            $module->getUmcModuleName(),
            'Source',
            'Catalog',
            'ProductAttribute'
        ];
        return implode('\\', $parts);
    }

    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        return true;
    }
}
