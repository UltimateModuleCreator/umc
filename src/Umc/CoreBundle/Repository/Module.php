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

namespace App\Umc\CoreBundle\Repository;

use App\Umc\CoreBundle\Config\Provider;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Service\FileFinderFactory;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Yaml\Yaml;

class Module
{
    public const FILE_TYPE = '*.yml';
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Provider\Factory
     */
    private $providerFactory;
    /**
     * @var string
     */
    private $root;
    /**
     * @var FileFinderFactory
     */
    private $finderFactory;
    /**
     * @var array
     */
    private $filesCache = [];

    /**
     * Module constructor.
     * @param Filesystem $filesystem
     * @param Provider\Factory $providerFactory
     * @param FileFinderFactory $finderFactory
     * @param string $root
     */
    public function __construct(
        Filesystem $filesystem,
        Provider\Factory $providerFactory,
        FileFinderFactory $finderFactory,
        string $root
    ) {
        $this->filesystem = $filesystem;
        $this->providerFactory = $providerFactory;
        $this->finderFactory = $finderFactory;
        $this->root = $root;
    }

    /**
     * @param \App\Umc\CoreBundle\Model\Module $module
     * @param Version $version
     */
    public function save(\App\Umc\CoreBundle\Model\Module $module, Version $version)
    {
        $name = $module->getExtensionName();
        $platform = $version->getPlatform();
        $file = $this->getRoot($version) . '/' . $name . '.yml';
        $content = [
            'meta' => [
                'platform' => $platform->getCode(),
                'version' => $version->getCode()
            ],
            'module' => $module->toArray()
        ];
        $this->filesystem->dumpFile(
            $file,
            Yaml::dump($content, 100, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE)
        );
    }

    /**
     * @param string $name
     * @param Version $version
     * @return array
     * @throws Exception
     */
    public function load(string $name, Version $version)
    {
        $name = basename($name);
        $file = $this->getRoot($version) . '/' . $name . '.yml';
        if (!$this->filesystem->exists($file)) {
            //TODO: create separate exception type
            throw new Exception("Module {$name} was not created for .... ");
        }
        return $this->providerFactory->create($file)->getConfig();
    }

    /**
     * @param Platform $platform
     * @return array
     */
    public function getPlatformModules(Platform $platform)
    {
        $files = [];
        foreach ($platform->getVersions() as $version) {
            $files[$version->getCode()] = $this->getVersionModules($version);
        }
        return $files;
    }

    /**
     * @param Version $version
     * @return array
     */
    public function getVersionModules(Version $version)
    {
        $list = [];
        $finder = $this->finderFactory->create();
        try {
            $finder->files()->in($this->getRoot($version))->depth('== 0')->name(self::FILE_TYPE)->sortByName();
            foreach ($finder->getIterator() as $file) {
                $list[] = [
                    'name' => $file->getFilename(),
                    'date' => \DateTimeImmutable::createFromFormat('U', (string)$file->getMTime()),
                    'module_name' => substr($file->getFilename(), 0, -strlen(self::FILE_TYPE) + 1),
                ];
            }
        } catch (DirectoryNotFoundException $e) {
            //do nothing here
        }
        return $list;
    }

    /**
     * @param Version $version
     * @return string
     */
    public function getRoot(Version $version)
    {
        $platform = $version->getPlatform();
        return $this->root . '/' . $platform->getCode() . '/' . $version->getCode();
    }
}
