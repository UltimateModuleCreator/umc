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
use App\Umc\CoreBundle\Util\Sorter;

class Builder
{
    /**
     * @var Factory
     */
    private $platformFactory;
    /**
     * @var \App\Umc\CoreBundle\Model\Platform\Version\Factory
     */
    private $versionFactory;
    /**
     * @var Sorter
     */
    private $sorter;

    /**
     * Builder constructor.
     * @param Factory $platformFactory
     * @param Version\Factory $versionFactory
     * @param Sorter $sorter
     */
    public function __construct(Factory $platformFactory, Version\Factory $versionFactory, Sorter $sorter)
    {
        $this->platformFactory = $platformFactory;
        $this->versionFactory = $versionFactory;
        $this->sorter = $sorter;
    }

    /**
     * @param array $data
     * @return Platform
     */
    public function build(array $data): Platform
    {
        $versions = [];
        if (isset($data['versions']) && is_array($data['versions'])) {
            $versionData = $this->sorter->sort($data['versions'], 'sort_weight', 1000, true);
            $versions = array_map(
                function ($item, $key) {
                    return $this->versionFactory->create($key, $item);
                },
                $versionData,
                array_keys($versionData)
            );
        }
        return $this->platformFactory->create($data, $versions);
    }
}
