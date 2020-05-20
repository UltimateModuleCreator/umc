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

namespace App\Umc\CoreBundle\Config\Modifier;

use App\Umc\CoreBundle\Util\Sorter;

class Sort implements ModifierInterface
{
    const SORT_ROOT = '/';
    /**
     * @var Sorter
     */
    private $sorter;
    /**
     * @var array
     */
    private $fields;

    /**
     * Sort constructor.
     * @param Sorter $sorter
     * @param array $fields
     */
    public function __construct(Sorter $sorter, array $fields)
    {
        $this->sorter = $sorter;
        $this->fields = $fields;
    }

    /**
     * @param array $config
     * @return array
     */
    public function modify(array $config): array
    {
        if (isset($this->fields[self::SORT_ROOT])) {
            $config = $this->sorter->sort(
                $config,
                $this->fields[self::SORT_ROOT]['key'] ?? Sorter::DEFAULT_SORT_ORDER_KEY
            );
            unset($this->fields[self::SORT_ROOT]);
        }
        foreach ($config as $key => $item) {
            foreach ($this->fields as $field => $settings) {
                if (isset($item[$field]) && is_array($item[$field])) {
                    $config[$key][$field] = $this->sorter->sort(
                        $item[$field],
                        $settings['key'] ?? Sorter::DEFAULT_SORT_ORDER_KEY
                    );
                }
            }
        }
        return $config;
    }
}
