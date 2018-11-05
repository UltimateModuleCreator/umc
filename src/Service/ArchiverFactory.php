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

class ArchiverFactory
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var FinderFactory
     */
    private $finderFactory;
    /**
     * @var ZipArchiveFactory
     */
    private $zipArchiveFactory;

    /**
     * ArchiverFactory constructor.
     * @param Filesystem $filesystem
     * @param FinderFactory $finderFactory
     * @param ZipArchiveFactory $zipArchiveFactory
     */
    public function __construct(
        Filesystem $filesystem,
        FinderFactory $finderFactory,
        ZipArchiveFactory $zipArchiveFactory
    ) {
        $this->filesystem = $filesystem;
        $this->finderFactory = $finderFactory;
        $this->zipArchiveFactory = $zipArchiveFactory;
    }

    /**
     * @param $basePath
     * @return Archiver
     */
    public function create($basePath) : Archiver
    {
        return new Archiver($this->filesystem, $basePath, $this->finderFactory, $this->zipArchiveFactory);
    }
}
