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

namespace App\Umc\CoreBundle\Config\Modifier\Expand;

class Pool
{
    /**
     * @var ExpandInterface[]
     */
    private $expands;

    /**
     * Pool constructor.
     * @param ExpandInterface[] $expands
     */
    public function __construct(iterable $expands = [])
    {
        $this->expands = [];
        foreach ($expands as $expander) {
            if (!($expander instanceof ExpandInterface)) {
                throw new \InvalidArgumentException(
                    "Config expand must implement " . ExpandInterface::class
                );
            }
            $this->expands[$expander->getCode()] = $expander;
        }
    }

    /**
     * @param $code
     * @return ExpandInterface
     */
    public function getExpand($code)
    {
        if (isset($this->expands[$code])) {
            return $this->expands[$code];
        }
        throw new MissingExpandException(
            "Config expand with code " . $code . " does not exist."
        );
    }
}
