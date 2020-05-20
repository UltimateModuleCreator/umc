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

/**
 * @deprecated
 */
class FrontendMenuLink// implements OptionProviderInterface
{
    public const NONE = 0;
    public const MAIN_MENU = 1;
    public const FOOTER = 2;

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            self::NONE => [
                'label' => 'No Link'
            ],
            self::MAIN_MENU => [
                'label' => 'Main Menu'
            ],
            self::FOOTER => [
                'label' => 'Footer links'
            ],
        ];
    }
}
