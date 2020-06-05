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

namespace App\Umc\MagentoBundle\Model\Attribute;

class Dynamic extends \App\Umc\CoreBundle\Model\Attribute\Dynamic
{
    /**
     * @return string
     */
    public function getOptionSourceVirtualType(): string
    {
        $parts = [
            $this->getAttribute()->getEntity()->getModule()->getModuleName(),
            $this->getAttribute()->getEntity()->getNameSingular(),
            'Source',
            $this->getAttribute()->getCode(),
            $this->getCode()
        ];
        return $this->stringUtil->glueClassParts($parts, '');
    }
}
