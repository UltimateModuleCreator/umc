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


namespace App\Umc\CoreBundle\Model\Config;

use App\Util\YamlLoader;

/**
 * @deprecated
 */
class ProviderFactory
{
    /**
     * @var YamlLoader
     */
    private $loader;

    /**
     * ProviderFactory constructor.
     * @param YamlLoader $loader
     */
    public function __construct(YamlLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param string $file
     * @return Provider
     */
    public function create(string $file): Provider
    {
        return new Provider($this->loader, $file);
    }
}
