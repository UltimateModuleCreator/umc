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

namespace App\Umc\CoreBundle\Model;

use App\Umc\CoreBundle\Model\Platform\Version;

class Platform
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var array
     */
    private $data;
    /**
     * @var Version[]
     */
    private $versions = [];

    /**
     * Platform constructor.
     * @param string $code
     * @param array $data
     * @param array $versions
     */
    public function __construct(string $code, array $data, array $versions)
    {
        $data['config'] = (array)($data['config'] ?? []);
        $this->code = $code;
        $this->data = $data;
        array_walk(
            $versions,
            function (Version $version) {
                $this->addVersion($version);
            }
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)($this->data['name'] ?? $this->code);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)($this->data['description'] ?? '');
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return isset($this->data['image']) ? (string)$this->data['image'] : null;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return isset($this->data['url']) ? (string)$this->data['url'] : null;
    }

    /**
     * @return string|null
     */
    public function getDestinationFolder(): ?string
    {
        $result = $this->getConfig('destination');
        return isset($result[0]) ? (string)$result[0] : null;
    }

    /**
     * @param null $key
     * @return array
     */
    public function getConfig($key = null): array
    {
        $config = $this->data['config'];
        if ($key === null) {
            return $config;
        }
        $parts = explode('.', $key);
        foreach ($parts as $part) {
            $config = $config[$part] ?? [];
        }
        return is_array($config) ? $config : [$config];
    }

    /**
     * @return Version[]
     */
    public function getVersions(): array
    {
        return $this->versions;
    }

    /**
     * @param string|null $code
     * @return Version
     */
    public function getVersion(?string $code = null): Version
    {
        if ($code !== null) {
            if (isset($this->versions[$code])) {
                return $this->versions[$code];
            }
            throw new \InvalidArgumentException(
                "There is no version with code {$code} for platform {$this->getName()}"
            );
        }
        return $this->getLatestVersion();
    }

    /**
     * @return Version
     * @throws \InvalidArgumentException
     */
    public function getLatestVersion(): Version
    {
        if (count($this->versions) > 0) {
            return array_values($this->versions)[0];
        }
        throw new \InvalidArgumentException(
            "There are no versions for platform {$this->getName()}"
        );
    }

    /**
     * @param Version $version
     */
    public function addVersion(Version $version): void
    {
        $version->setPlatform($this);
        $this->versions[$version->getCode()] = $version;
    }

    /**
     * @return bool
     */
    public function isSupported(): bool
    {
        return count($this->getVersions()) > 0;
    }
}
