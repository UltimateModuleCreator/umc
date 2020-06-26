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

namespace App\Umc\CoreBundle\Model\Platform;

use App\Umc\CoreBundle\Model\Platform;

class Factory
{
    /**
     * @param array $data
     * @param Version[] $versions
     * @return Platform
     */
    public function create(array $data, array $versions): Platform
    {
        return new Platform($data['code'] ?? '', $data, $versions);
    }
}
