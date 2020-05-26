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

namespace App\Umc\CoreBundle\Config\Source;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Config implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('source');
        $root = $builder->getRootNode();
        $root->useAttributeAsKey('source', false)
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('source')->end()
                        ->scalarNode('label')->end()
                        ->scalarNode('destination')->end()
                        ->enumNode('scope')->values(['module', 'entity', 'attribute'])->defaultValue('module')->end()
                    ->end()
                    ->beforeNormalization()
                    ->always(
                        function ($config) {
                            $source = $config['source'];
                            if (substr($source, 0, strlen($source) - strlen('.html.twig')) !== '.html.twig') {
                                $config['source'] .= '.html.twig';
                            }
                            if (!isset($config['destination'])) {
                                $source = $config['source'];
                                $destination = substr($source, 0, strlen($source) - strlen('.html.twig'));
                                if (substr($destination, 0, 1) === '@') {
                                    $parts = explode('/', $destination);
                                    unset($parts[0]);
                                    unset($parts[1]);
                                    $destination = implode('/', $parts);
                                }
                                $config['destination'] = $destination;
                            }
                            return $config;
                        }
                    )->end()
                ->end()
            ->end();
        return $builder;
    }
}
