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

namespace App\Model\Attribute\Serialized\Option;

use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Option;

class Factory
{
    /**
     * @param Serialized $field
     * @param array $data
     * @return Option
     */
    public function create(Serialized $field, array $data = []): Option
    {
        return new Option($field, $data);
    }
}
