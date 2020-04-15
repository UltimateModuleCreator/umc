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

namespace App\Service\Form\OptionProvider;

use App\Service\Form\OptionProviderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class AdminMenu implements OptionProviderInterface
{
    public const CONFIG_PARAM_NAME = 'menu_type_config';
    /**
     * @var ContainerBagInterface
     */
    private $containerBag;
    /**
     * @var array
     */
    private $options;

    /**
     * AdminMenu constructor.
     * @param ContainerBagInterface $containerBag
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->containerBag = $containerBag;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if ($this->options === null) {
            $this->options = $this->containerBag->get(self::CONFIG_PARAM_NAME);
        }
        return $this->options;
    }
}
