<?php

/**
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

namespace App\Umc\MagentoBundle\Model\Attribute\Type;

use App\Umc\MagentoBundle\Model\Attribute;

/**
 * @method getAttribute(): Attribute
 */
class BaseType extends \App\Umc\CoreBundle\Model\Attribute\Type\BaseType
{
    /**
     * @return string
     */
    public function getSourceModel(): string
    {
        return $this->sourceModel ?? $this->getAttribute()->getOptionSourceVirtualType();
    }

    /**
     * @return string
     */
    public function getIndexDeleteType(): string
    {
        return $this->getAttribute()->isRequired() ? 'CASCADE' : 'SET NULL';
    }

    /**
     * @return string
     */
    public function getAttributeColumnSettingsStringXml(): string
    {
        $attributes = $this->getSchemaAttributes();
        if (strlen($attributes) > 0) {
            $attributes .= ' ';
        }
        $attributes .= 'nullable="' . ($this->getAttribute()->isRequired() ? 'false' : 'true') . '"';
        return $attributes;
    }
}
