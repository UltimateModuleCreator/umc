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

use App\Umc\CoreBundle\Config\Provider\Factory;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Repository\Settings\MissingSettingsFileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Settings
{
    public const FILENAME = 'default.yml';
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Factory
     */
    private $providerFactory;
    /**
     * @var string
     */
    private $root;

    /**
     * Settings constructor.
     * @param Filesystem $filesystem
     * @param Factory $providerFactory
     * @param string $root
     */
    public function __construct(
        Filesystem $filesystem,
        Factory $providerFactory,
        string $root
    ) {
        $this->filesystem = $filesystem;
        $this->providerFactory = $providerFactory;
        $this->root = $root;
    }

    /**
     * @param Platform $platform
     * @return string
     */
    private function getPlatformRoot(Platform $platform)
    {
        return $this->root . '/' . $platform->getCode();
    }

    /**
     * @param Platform\Version $version
     * @return string
     */
    private function getVersionRoot(Platform\Version $version)
    {
        return $this->getPlatformRoot($version->getPlatform()) . '/' . $version->getCode();
    }

    /**
     * @param string $file
     * @param array $config
     */
    private function save(string $file, array $config): void
    {
        $this->filesystem->dumpFile(
            $file,
            Yaml::dump($config, 100, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE)
        );
    }

    /**
     * @param string $file
     * @return array
     * @throws MissingSettingsFileException
     */
    private function load(string $file): array
    {
        if (!$this->filesystem->exists($file)) {
            throw new MissingSettingsFileException();
        }
        return $this->providerFactory->create($file)->getConfig();
    }

    /**
     * @param string $file
     * @throws MissingSettingsFileException
     */
    private function delete(string $file): void
    {
        if (!$this->filesystem->exists($file)) {
            throw new MissingSettingsFileException();
        }
        $this->filesystem->remove($file);
    }

    /**
     * @param Platform $platform
     * @param array $config
     */
    public function savePlatformConfig(Platform $platform, array $config): void
    {
        $this->save($this->getPlatformRoot($platform) . '/' . self::FILENAME, $config);
    }

    /**
     * @param Platform $platform
     * @return array
     */
    public function loadPlatformConfig(Platform $platform): array
    {
        try {
            return $this->load($this->getPlatformRoot($platform) . '/' . self::FILENAME);
        } catch (MissingSettingsFileException $e) {
            return [];
        }
    }

    /**
     * @param Platform $platform
     */
    public function deletePlatformConfig(Platform $platform)
    {
        try {
            $this->delete($this->getPlatformRoot($platform) . '/' . self::FILENAME);
        } catch (MissingSettingsFileException $e) {
            //do nothing
        }
    }

    /**
     * @param Platform\Version $version
     * @param bool $defaultToPlatform
     * @return array
     * @throws MissingSettingsFileException
     */
    public function loadVersionConfig(Platform\Version $version, $defaultToPlatform = true): array
    {
        try {
            return $this->load($this->getVersionRoot($version) . '/' . self::FILENAME);
        } catch (MissingSettingsFileException $e) {
            if ($defaultToPlatform) {
                return $this->loadPlatformConfig($version->getPlatform());
            }
            throw $e;
        }
    }

    /**
     * @param Platform\Version $version
     * @param array $config
     */
    public function saveVersionConfig(Platform\Version $version, array $config): void
    {
        $this->save($this->getVersionRoot($version) . '/' . self::FILENAME, $config);
    }

    /**
     * @param Platform\Version $version
     */
    public function deleteVersionConfig(Platform\Version $version): void
    {
        try {
            $this->delete($this->getVersionRoot($version) . '/' . self::FILENAME);
        } catch (MissingSettingsFileException $e) {
            //do nothing
        }
    }
}
