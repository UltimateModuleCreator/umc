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

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Model\Platform;

class Pool
{
    /**
     * @var Loader
     */
    private $configLoader;
    /**
     * @var Builder
     */
    private $builder;
    /**
     * @var Platform[]
     */
    private $platforms;

    /**
     * Pool constructor.
     * @param Loader $configLoader
     * @param Builder $builder
     */
    public function __construct(Loader $configLoader, Builder $builder)
    {
        $this->configLoader = $configLoader;
        $this->builder = $builder;
    }

    /**
     * @return Platform[]
     */
    public function getPlatforms(): array
    {
        if ($this->platforms === null) {
            foreach ($this->configLoader->getConfig() as $key => $item) {
                $this->platforms[$key] = $this->builder->build($item);
            }
        }
        return $this->platforms;
    }

    /**
     * @param string $code
     * @return Platform
     */
    public function getPlatform(string $code): Platform
    {
        $platforms = $this->getPlatforms();
        if (isset($platforms[$code])) {
            return $platforms[$code];
        }
        throw new \InvalidArgumentException("Platform with code {$code} does not exist");
    }
}
