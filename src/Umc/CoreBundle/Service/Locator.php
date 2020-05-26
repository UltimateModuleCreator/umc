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

namespace App\Umc\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Locator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Locator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $id
     * @return object|null
     */
    public function getService($id)
    {
        return $this->container->get($id);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
