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

namespace App\Umc\MagentoBundle\Model\Attribute\Dynamic\Type;

use App\Umc\MagentoBundle\Model\Attribute;
use App\Umc\MagentoBundle\Model\Entity;

/**
 * @method getDynamic() : Dynamic
 */
class ProductAttributeSet extends BaseType
{
    /**
     * @return string
     */
    public function getSourceModel(): string
    {
        $dynamic= $this->getDynamic();
        /** @var Attribute $attribute */
        $attribute = $dynamic->getAttribute();
        /** @var Entity $entity */
        $entity = $attribute->getEntity();
        $module = $entity->getModule();
        $parts = [
            $module->getUmcCrudNamespace(),
            $module->getUmcModuleName(),
            'Source',
            'Catalog',
            'ProductAttributeSet'
        ];
        return implode('\\', $parts);
    }


}
