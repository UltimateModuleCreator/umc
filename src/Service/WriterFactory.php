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

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class WriterFactory
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * WriterFactory constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $basePath
     * @return Writer
     */
    public function create(string $basePath): Writer
    {
        return new Writer($basePath, $this->filesystem);
    }
}
