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

namespace App\Umc\MagentoBundle\Model\OptionProvider;

use App\Umc\CoreBundle\Model\Config\ProviderInterface;
use App\Umc\CoreBundle\Model\OptionProvider\OptionProviderInterface;

class AdminMenu implements OptionProviderInterface
{
    public const MAGENTO_ADMIN_MENU = 'magento_admin_menu';
    /**
     * @var ProviderInterface
     */
    private $provider;
    /**
     * @var array
     */
    private $menu;

    /**
     * AdminMenu constructor.
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getOptions(): array
    {
        if ($this->menu === null) {
            $this->menu = $this->provider->getConfig();
        }
        return $this->menu;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return self::MAGENTO_ADMIN_MENU;
    }
}
