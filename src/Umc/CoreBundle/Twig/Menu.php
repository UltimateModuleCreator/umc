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

namespace App\Umc\CoreBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Menu extends AbstractExtension
{
    /**
     * @var \App\Umc\CoreBundle\Service\Menu
     */
    private $menu;

    /**
     * Menu constructor.
     * @param \App\Umc\CoreBundle\Service\Menu $menu
     */
    public function __construct(\App\Umc\CoreBundle\Service\Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'get_main_menu',
                function () {
                    return $this->menu->getItems();
                }
            )
        ];
    }
}
