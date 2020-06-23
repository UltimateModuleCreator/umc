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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Config implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('platforms');
        $root = $builder->getRootNode();
        $root->useAttributeAsKey('code', false)
            ->arrayPrototype()
            ->children()
                ->scalarNode('code')->end() //code of the platform
                ->scalarNode('name')->isRequired()->end() // name of the platform
                ->scalarNode('url')->end() // url to platform website
                ->scalarNode('image')->end()
                ->integerNode('sort_order')->defaultValue(1000)->end()
                ->scalarNode('unsupported_message')->end() //message shown when platform is not supported
                ->arrayNode('config') //different configuration stuff.
                    ->children()
                        ->arrayNode('form') //files loaded for form UI
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('source')//files loaded for the source of the modules
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('coding_standards') //coding standards executed after code is generated
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('extra_vars')
                            ->prototype('scalar')->end()
                        ->end() //vars passed to the generators
                        ->scalarNode('destination')->end() //huh? I forgot
                        ->scalarNode('module_factory')->end() //service Id of the module factory
                        ->scalarNode('generator_pool') //service id of the generator pool
                            ->defaultValue('default.generator.pool')
                        ->end()
                        ->scalarNode('validator_pool') //service id of the module validator pool
                            ->defaultValue('default.validator.pool')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('versions')
                    ->useAttributeAsKey('code')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->isRequired()->end() //varsion label
                            ->scalarNode('code')->end() //version code
                            ->integerNode('sort_weight')->defaultValue(0)->end()
                            ->arrayNode('config') //different config types more or less like the platform
                                ->children()
                                    ->arrayNode('form')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('coding_standards')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('extra_vars')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('source')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->scalarNode('module_factory')->end()
                                    ->scalarNode('generator_pool')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        return $builder;
    }
}
