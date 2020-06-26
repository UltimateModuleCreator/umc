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

class Composite implements ModifierInterface
{
    /**
     * @var ModifierInterface[]
     */
    private $modifiers;

    /**
     * Composite constructor.
     * @param ModifierInterface[] $modifiers
     */
    public function __construct(iterable $modifiers)
    {
        $this->modifiers = [];
        foreach ($modifiers as $modifier) {
            if (!$modifier instanceof ModifierInterface) {
                throw new \InvalidArgumentException(
                    "Config modifiers should implement " . ModifierInterface::class
                );
            }
            $this->modifiers[] = $modifier;
        }
    }

    /**
     * @param array $config
     * @return array
     */
    public function modify(array $config): array
    {
        return array_reduce(
            $this->modifiers,
            function ($config, ModifierInterface $processor) {
                return $processor->modify($config);
            },
            $config
        );
    }
}
