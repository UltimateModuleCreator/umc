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

namespace App\Umc\CoreBundle\Service\Validator;

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
     * @return Pool
     * @throws \App\Umc\CoreBundle\Model\Platform\ConfigException
     */
    public function getValidatorPool(Version $version): Pool
    {
        $validatorPool = $this->serviceLocator->getService($version->getValidatorPoolServiceId());
        if (!$validatorPool instanceof Pool) {
            throw new \InvalidArgumentException("Module validator pool should be instance of " . Pool::class);
        }
        return $validatorPool;
    }
}
