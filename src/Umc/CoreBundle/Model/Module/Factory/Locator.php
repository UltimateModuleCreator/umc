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

class Locator
{
    /**
     * @var \App\Umc\CoreBundle\Service\Locator
     */
    private $serviceLocator;

    /**
     * Locator constructor.
     * @param \App\Umc\CoreBundle\Service\Locator $serviceLocator
     */
    public function __construct(\App\Umc\CoreBundle\Service\Locator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param Version $version
     * @return Factory
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getFactory(Version $version): Factory
    {
        $factory = $this->serviceLocator->getService($version->getModuleFactoryServiceId());
        if (!$factory instanceof Factory) {
            throw new \InvalidArgumentException("Module factory should be instance of " . Factory::class);
        }
        return $factory;
    }
}
