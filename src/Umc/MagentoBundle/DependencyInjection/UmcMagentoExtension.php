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
 *
 */

declare(strict_types=1);

namespace App\Umc\MagentoBundle\DependencyInjection;

use App\Umc\CoreBundle\Config\FileLoader;
use App\Umc\CoreBundle\Config\ParamMerger;
use App\Umc\CoreBundle\Config\Provider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class UmcMagentoExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator((__DIR__.'/../Resources/config'))
        );
        $loader->load('services.xml');
        $provider = new Provider(new FileLoader(), __DIR__.'/../Resources/config/extensible_parameters.yml');
        $merger = new ParamMerger();
        $container->getParameterBag()->add(
            $merger->mergeParams($container->getParameterBag(), $provider->getConfig())
        );
    }
}
