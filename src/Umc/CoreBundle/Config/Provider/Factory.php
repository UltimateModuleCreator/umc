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

namespace App\Umc\CoreBundle\Config\Provider;

use App\Umc\CoreBundle\Config\FileLoader;
use App\Umc\CoreBundle\Config\Provider;

class Factory
{
    /**
     * @var FileLoader
     */
    private $fileLoader;

    /**
     * Factory constructor.
     * @param FileLoader $fileLoader
     */
    public function __construct(FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * @param string $file
     * @return Provider
     */
    public function create(string $file)
    {
        return new Provider($this->fileLoader, $file);
    }
}
