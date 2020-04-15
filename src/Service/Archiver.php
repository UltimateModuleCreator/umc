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

use App\Util\FinderFactory;
use Symfony\Component\Filesystem\Filesystem;

class Archiver
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $baseDestination;
    /**
     * @var FinderFactory
     */
    private $finderFactory;
    /**
     * @var ZipArchiveFactory
     */
    private $zipArchiveFactory;

    /**
     * Archiver constructor.
     * @param Filesystem $filesystem
     * @param string $baseDestination
     * @param FinderFactory $finderFactory
     * @param ZipArchiveFactory $zipArchiveFactory
     */
    public function __construct(
        Filesystem $filesystem,
        string $baseDestination,
        FinderFactory $finderFactory,
        ZipArchiveFactory $zipArchiveFactory
    ) {
        $this->filesystem = $filesystem;
        $this->baseDestination = $baseDestination;
        $this->finderFactory = $finderFactory;
        $this->zipArchiveFactory = $zipArchiveFactory;
    }

    /**
     * @param $source
     * @param $destination
     * @param bool $removeSource
     */
    public function createZip($source, $zipName, $removeSource = true): void
    {
        $source = realpath($source) ? realpath($source) : '';
        $destination = $this->baseDestination . $zipName . '.zip';
        $zip = $this->zipArchiveFactory->create();
        $zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $finder = $this->finderFactory->create();
        $finder->in($source);
        foreach ($finder->getIterator() as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        if ($removeSource) {
            $this->filesystem->remove($source);
        }
    }
}
