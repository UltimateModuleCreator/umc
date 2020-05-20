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

namespace App\Umc\CoreBundle\Util;

class Sorter
{
    public const DEFAULT_SORT_ORDER_KEY = 'sort_order';
    public const DEFAULT_SORT_ORDER_VALUE = 0;
    /**
     * @param array $items
     * @param string $key
     * @param int $default
     * @param bool $decreasing
     * @return array
     */
    public function sort(
        array $items,
        string $key = self::DEFAULT_SORT_ORDER_KEY,
        int $default = self::DEFAULT_SORT_ORDER_VALUE,
        bool $decreasing = false
    ): array {
        uasort(
            $items,
            function ($itemA, $itemB) use ($key, $default) {
                $valueA = $itemA[$key] ?? $default;
                $valueB = $itemB[$key] ?? $default;
                return $valueA <=> $valueB;
            }
        );
        return ($decreasing) ? array_reverse($items, true) : $items;
    }
}
