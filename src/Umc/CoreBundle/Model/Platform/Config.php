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
                ->scalarNode('code')->end()
                ->scalarNode('name')->isRequired()->end()
                ->scalarNode('url')->end()
                ->scalarNode('image')->end()
                ->integerNode('sort_order')->defaultValue(1000)->end()
                ->arrayNode('config')
                    ->children()
                        ->arrayNode('form')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('source')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('coding_standards')
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('destination')->end()
                        ->scalarNode('module_factory')->end()
                        ->scalarNode('generator_pool')->defaultValue('default.generator.pool')->end()
                        ->scalarNode('validator_pool')->defaultValue('default.validator.pool')->end()
                    ->end()
                ->end()
                ->arrayNode('versions')
                    ->useAttributeAsKey('code')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('code')->end()
                            ->integerNode('sort_weight')->defaultValue(0)->end()
                            ->arrayNode('config')
                                ->children()
                                    ->arrayNode('form')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('coding_standards')
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
