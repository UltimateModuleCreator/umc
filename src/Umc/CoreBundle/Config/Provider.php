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

namespace App\Umc\CoreBundle\Config;

class Provider
{
    /**
     * @var FileLoader
     */
    private $loader;
    /**
     * @var string
     */
    private $file;

    /**
     * Provider constructor.
     * @param FileLoader $loader
     * @param string $file
     */
    public function __construct(FileLoader $loader, string $file)
    {
        $this->loader = $loader;
        $this->file = $file;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getConfig(): array
    {
        return $this->loader->load($this->file);
    }
}
