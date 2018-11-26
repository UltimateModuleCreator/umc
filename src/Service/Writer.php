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

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class Writer
{
    /**
     * @var string
     */
    private $basePath;
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Writer constructor.
     * @param string $basePath
     * @param Filesystem $filesystem
     */
    public function __construct(string $basePath, Filesystem $filesystem)
    {
        $this->basePath = $basePath;
        $this->filesystem = $filesystem;
    }

    /**
     * @param array $files
     * @param ?string $subFolder
     * @return void
     */
    public function writeFiles(array $files, ?string $subFolder = null) : void
    {
        if (!$this->filesystem->exists($this->basePath)) {
            $this->filesystem->mkdir($this->basePath);
        }
        foreach ($files as $destination => $content) {
            $this->writeFile($destination, $content, $subFolder);
        }
    }

    /**
     * @param string $destination
     * @param string $content
     * @param string $subFolder
     */
    private function writeFile(string $destination, string $content, ?string $subFolder = null) : void
    {
        $destination = $this->basePath . (($subFolder) ? $subFolder . '/' : '') . $destination;
        $folder = dirname($destination);
        if (!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($folder);
        }
        $this->filesystem->dumpFile($destination, $content);
    }
}
