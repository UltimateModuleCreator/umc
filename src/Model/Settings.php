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
 */
declare(strict_types=1);

namespace App\Model;

use App\Util\YamlLoader;

class Settings
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var string
     */
    private $path;
    /**
     * @var array
     */
    private $settings;
    /**
     * @var YamlLoader
     */
    private $loader;

    /**
     * Settings constructor.
     * @param string $file
     * @param string $path
     * @param YamlLoader $loader
     */
    public function __construct(string $file, string $path, YamlLoader $loader)
    {
        $this->file = $file;
        $this->path = $path;
        $this->loader = $loader;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getSettingsAsYml() : string
    {
        return $this->loader->arrayToYaml($this->getSettings());
    }

    /**
     * @return array
     */
    public function getSettings($reload = false) : array
    {
        if ($this->settings === null || $reload) {
            try {
                $this->settings = $this->loader->load($this->path . '/' . $this->file);
            } catch (\Exception $e) {
                $this->settings = [];
            }
        }
        return $this->settings;
    }

    /**
     * @param bool $reload
     * @return array
     */
    public function getSettingsSplit($reload = false): array
    {
        $settings = $this->getSettings($reload);
        $split = [];
        foreach ($settings as $key => $value) {
            $parts = explode('_', $key, 2);
            $entity = $parts[0];
            $field = $parts[1];
            $split[$entity] = $split[$entity] ?? [];
            $split[$entity][$field] = $value;
        }
        return $split;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }
}
