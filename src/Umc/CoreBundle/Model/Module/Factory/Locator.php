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

namespace App\Umc\CoreBundle\Model\Module\Factory;

use App\Umc\CoreBundle\Model\Module\Factory;
use App\Umc\CoreBundle\Model\Platform\Version;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Locator implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Factory[]
     */
    private $cache = [];

    /**
     * Locator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Version $version
     * @return Factory
     * @throws \Exception
     */
    public function getFactory(Version $version): Factory
    {
        $platform = $version->getPlatform();
        $cacheKey = $platform->getCode() . '##' . $version->getCode();
        if (!array_key_exists($cacheKey, $this->cache)) {
            $serviceId = $version->getModuleFactoryServiceId();
            $this->cache[$cacheKey] = $this->container->get($serviceId);
        }
        return $this->cache[$cacheKey];
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
