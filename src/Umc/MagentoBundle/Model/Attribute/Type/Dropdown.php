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

namespace App\Umc\MagentoBundle\Model\Attribute\Type;

class Dropdown extends BaseType
{
    /**
     * @return string
     */
    public function getSchemaType(): string
    {
        return !$this->getAttribute()->areOptionsNumerical() ? 'varchar' : parent::getSchemaType();
    }

    /**
     * @return string
     */
    public function getSchemaAttributes(): string
    {
        return !$this->getAttribute()->areOptionsNumerical() ? ' length="255"' : parent::getSchemaAttributes();
    }
}
