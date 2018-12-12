<?php
/**
 *
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
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Menu extends AbstractExtension
{
    /**
     * @var \App\Service\Menu
     */
    private $menu;

    /**
     * Menu constructor.
     * @param \App\Service\Menu $menu
     */
    public function __construct(\App\Service\Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFunctions() : array
    {
        $menu = $this->menu;
        return [
            new TwigFunction(
                'appMenu',
                function () use ($menu) {
                    return $menu;
                }
            )
        ];
    }
}
