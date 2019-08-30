<?php
/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 *  that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */
namespace App\Model\Section;

use App\Model\Section;
use App\Util\FinderFactory;
use App\Util\YamlLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Filesystem\Filesystem;

class Loader
{
    /**
     * files to find
     */
    const FILE_TYPE = '*.yml';
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $basePath;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var FinderFactory
     */
    private $finderFactory;
    /**
     * @var Section[]
     */
    private $sectionTree;

    /**
     * Loader constructor.
     * @param YamlLoader $yamlLoader
     * @param string $basePath
     * @param Filesystem $filesystem
     * @param FinderFactory $finderFactory
     */
    public function __construct(
        YamlLoader $yamlLoader,
        Filesystem $filesystem,
        FinderFactory $finderFactory,
        string $basePath
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->basePath = $basePath;
        $this->filesystem = $filesystem;
        $this->finderFactory = $finderFactory;
    }


    public function getSectionTree()
    {
        if ($this->sectionTree === null) {
            $finder = $this->finderFactory->create();
            $finder->files()->in($this->basePath)->depth('== 0')->name(self::FILE_TYPE);
            $sections = [];
            foreach ($finder->getIterator() as $file) {
                $content = $this->yamlLoader->load($file);
                $sections = array_merge($sections, $content);
            }
        }
        var_dump($sections);exit;
        $this->generateTree($sections);
    }

    private function generateTree($data)
    {
        $sortedData = [];
        foreach ($data as $item) {
//            if (!isset($item['dependencies']))
        }
        $tree = new TreeBuilder('fake_root');
    }

}
