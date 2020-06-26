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

class Dynamic extends BaseType
{
    /**
     * @return array
     */
    public function getFlags()
    {
        $flags = parent::getFlags();
        /** @var Attribute $attribute */
        $attribute = $this->getAttribute();
        foreach ($attribute->getDynamic() as $field) {
            $flags = array_merge($flags, $field->getTypeInstance()->getFlags());
        }
        return array_unique($flags);
    }
}
