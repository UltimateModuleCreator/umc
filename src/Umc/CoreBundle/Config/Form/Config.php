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

namespace App\Umc\CoreBundle\Config\Form;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Config implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('form');
        $root = $builder->getRootNode();
        $root->useAttributeAsKey('code')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('settings_label')->end()
                    ->scalarNode('template')->defaultValue('@UmcCore/edit/form.html.twig')->end()
                    ->arrayNode('panel')
                        ->children()
                            ->arrayNode('fields')
                                ->useAttributeAsKey('field', false)
                                ->arrayPrototype()
                                    ->canBeDisabled()
                                    ->children()
                                        ->scalarNode('field')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('default')->isRequired()->end()
                        ->end()
                    ->end()
                    ->arrayNode('children')
                        ->useAttributeAsKey('code', false)
                        ->arrayPrototype()
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('code')->end()
                                ->scalarNode('className')->isRequired()->end()
                                ->scalarNode('tabLabel')->isRequired()->end()
                                ->scalarNode('formKey')->isRequired()->end()
                                ->scalarNode('sortable')->end()
                                ->scalarNode('visible')->end()
                                ->arrayNode('addButton')
                                    ->children()
                                        ->scalarNode('label')->defaultValue('Add')->end()
                                        ->arrayNode('split')
                                            ->children()
                                                ->scalarNode('field')->end()
                                                ->scalarNode('values')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('tabs')
                        ->useAttributeAsKey('code', false)
                        ->arrayPrototype()
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('code')->end()
                                ->scalarNode('label')->end()
                                ->integerNode('sort_order')->defaultValue(1000)->end()
                                ->enumNode('cols')->values([1, 2, 3, 4, 6, 12])->defaultValue(3)->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('fields')
                        ->useAttributeAsKey('name', false)
                            ->arrayPrototype()
                            ->canBeEnabled()
                            ->beforeNormalization()
                            ->always(
                                function ($config) {
                                    if (!isset($config['template']) && isset($config['type'])) {
                                        $config['template'] = '@UmcCore/edit/form/field/' .
                                            $config['type'] . '.html.twig';
                                    }
                                    return $config;
                                }
                            )
                            ->end()
                            ->children()
                                ->scalarNode('name')->end()
                                ->scalarNode('type')->end()
                                ->scalarNode('label')->end()
                                ->booleanNode('has_default')->defaultValue(false)->end()
                                ->scalarNode('required')->end()
                                ->scalarNode('additionalDataBind')->end()
                                ->scalarNode('options')->end()
                                ->scalarNode('dataValidation')->end()
                                ->scalarNode('dataValidationRegexp')->end()
                                ->scalarNode('dataValidationErrorMsg')->end()
                                ->scalarNode('title')->end()
                                ->scalarNode('containerAttributes')->end()
                                ->integerNode('sort_order')->defaultValue(1000)->end()
                                ->scalarNode('tab')->end()
                                ->scalarNode('template')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        return $builder;
    }
}
