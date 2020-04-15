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
 */

declare(strict_types=1);

namespace App\Service\Form;

class OptionProviderPool
{
    /**
     * @var OptionProviderInterface[]
     */
    private $providers;

    /**
     * OptionProviderPool constructor.
     * @param OptionProviderInterface[] $providers
     */
    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            if (!($provider instanceof OptionProviderInterface)) {
                throw new \InvalidArgumentException(
                    "Option providers must implement " . OptionProviderInterface::class
                );
            }
        }
        $this->providers = $providers;
    }

    /**
     * @param $code
     * @return OptionProviderInterface
     */
    public function getProvider($code)
    {
        if (isset($this->providers[$code])) {
            return $this->providers[$code];
        }
        throw new \InvalidArgumentException(
            "Option provider with code " . $code . " does not exist."
        );
    }
}
