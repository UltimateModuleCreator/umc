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

namespace App\Umc\CoreBundle\Model\Platform;

use App\Umc\CoreBundle\Model\Platform;

class Version
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var array[]
     */
    private $data;
    /**
     * @var Platform
     */
    private $platform;

    /**
     * Version constructor.
     * @param string $code
     * @param array $data
     */
    public function __construct(string $code, array $data)
    {
        $data['config'] = $data['config'] ?? [];
        $this->code = $code;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return (string)($this->data['label'] ?? $this->code);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string|null $key
     * @param bool $withPlatform
     * @return array
     */
    public function getConfig(string $key = null, $withPlatform = true): array
    {
        $config = $this->data['config'];
        if ($key === null) {
            return ($withPlatform)
                ? array_replace_recursive($this->getPlatform()->getConfig(), $config)
                : $config;
        }
        $parts = explode('.', $key);
        foreach ($parts as $part) {
            $config = $config[$part] ?? [];
        }
        $config = is_array($config) ? $config : [$config];
        return ($withPlatform) ? array_replace_recursive($this->getPlatform()->getConfig($key), $config) : $config;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getModuleFactoryServiceId(): string
    {
        $config = $this->getConfig('module_factory');
        if (!isset($config[0])) {
            throw new ConfigException(
                "Missing Module factory for platform {$this->getPlatform()->getName()}: {$this->getLabel()}"
            );
        }
        return $config[0];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getGeneratorPoolServiceId(): string
    {
        $config = $this->getConfig('generator_pool');
        if (!isset($config[0])) {
            throw new ConfigException(
                "Missing Generator pool for platform {$this->getPlatform()->getName()}: {$this->getLabel()}"
            );
        }
        return $config[0];
    }

    /**
     * @param bool $withPlatform
     * @return array
     */
    public function getCodingStandards($withPlatform = true)
    {
        return $this->getConfig('coding_standards', $withPlatform);
    }

    /**
     * @return Platform
     */
    public function getPlatform(): Platform
    {
        return $this->platform;
    }

    /**
     * @param Platform $platform
     */
    public function setPlatform(Platform $platform): void
    {
        $this->platform = $platform;
    }
}
