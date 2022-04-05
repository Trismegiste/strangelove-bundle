<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The config
 */
class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('trismegiste_strangelove');

        $treeBuilder->getRootNode()
                ->children()
                    ->arrayNode('mongodb')
                        ->children()
                            ->scalarNode('url')
                                ->defaultValue('mongodb://localhost:27017')
                            ->end()
                            ->scalarNode('dbname')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }

}
