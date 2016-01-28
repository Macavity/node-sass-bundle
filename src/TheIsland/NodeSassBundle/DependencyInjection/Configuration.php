<?php

namespace TheIsland\NodeSassBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("node_sass", 'array');

        $rootNode
            ->children()
                ->scalarNode('apply_to')->end()
                ->scalarNode('bin')->end()
                ->scalarNode('source_map')->defaultValue(false)->end()
                ->scalarNode('node')->defaultNull()->end()
                ->scalarNode('style')->end()
                ->booleanNode('debug')->defaultFalse()->end()
                ->arrayNode('load_paths')->prototype('scalar')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
