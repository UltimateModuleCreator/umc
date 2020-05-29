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

class Remove implements ModifierInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * Remove constructor.
     * @param $key
     */
    public function __construct(string $key = 'enabled')
    {
        $this->key = $key;
    }

    /**
     * @param array $config
     * @return array
     */
    public function modify(array $config): array
    {
        $processed = [];
        foreach ($config as $key => $item) {
            if (\is_array($item)) {
                if (isset($item[$this->key]) && !$item[$this->key]) {
                    continue;
                } else {
                    $processed[$key] = $this->modify($item);
                }
            } else {
                $processed[$key] = $item;
            }
        }
        return $processed;
    }
}
