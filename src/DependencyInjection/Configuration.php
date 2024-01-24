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

    /**
     * Generates the configuration for Strangelove bundle
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('strangelove');

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
