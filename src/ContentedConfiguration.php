<?php

namespace Contented;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ContentedConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contented');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('content_path')->defaultValue('%kernel.project_dir%/var/content')->end()
                ->arrayNode('languages')->prototype('scalar')->end()->end()
            ?->end()
        ;

        return $treeBuilder;
    }
}