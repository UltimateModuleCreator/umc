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

namespace App\Umc\CoreBundle\Model\Attribute\Option;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Option;

class Factory
{
    /**
     * @param Attribute $attribute
     * @param array $data
     * @return Option
     */
    public function create(Attribute $attribute, array $data = []): Option
    {
        return new Option($attribute, $data);
    }
}
