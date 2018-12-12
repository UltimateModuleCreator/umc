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

use App\Util\FinderFactory;
use Symfony\Component\Filesystem\Filesystem;

class ModuleList
{
    /**
     * @var FinderFactory
     */
    private $finderFactory;
    /**
     * @var string
     */
    private $basePath;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string[] | null
     */
    private $modules;
    /**
     * files to find
     */
    const FILE_TYPE = '*.yml';

    /**
     * ModuleList constructor.
     * @param FinderFactory $finderFactory
     * @param string $basePath
     * @param Filesystem $filesystem
     */
    public function __construct(FinderFactory $finderFactory, Filesystem $filesystem, $basePath)
    {
        $this->finderFactory = $finderFactory;
        $this->basePath = $basePath;
        $this->filesystem = $filesystem;
    }

    /**
     * @return array
     */
    public function getModules() : array
    {
        if ($this->modules === null) {
            $this->createBaseDir();
            $finder = $this->finderFactory->create();
            $finder->files()->in($this->basePath)->depth('== 0')->name(self::FILE_TYPE)->sortByName();
            $this->modules = [];
            foreach ($finder->getIterator() as $file) {
                $this->modules[] = [
                    'name' => $file->getFilename(),
                    'date' => \DateTimeImmutable::createFromFormat('U', (string)$file->getMTime()),
                    'module_name' => substr($file->getFilename(), 0, -strlen(self::FILE_TYPE) + 1)
                ];
            }
        }
        return $this->modules;
    }

    /**
     * create the base dir if it does not exist
     */
    private function createBaseDir() : void
    {
        if (!$this->filesystem->exists($this->basePath)) {
            $this->filesystem->mkdir($this->basePath);
        }
    }
}
