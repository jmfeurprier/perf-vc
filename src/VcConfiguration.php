<?php

namespace perf\Vc;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class VcConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('perf.vc');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('controllers_namespace')
                    ->info('Namespace of controller classes.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('routing_rules_file_path')
                    ->info('Path to routing rules file.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('view_files_base_path')
                    ->info('Base path to view (template) files.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('view_files_extension')
                    ->info('File extension of view (template) files.')
                    ->defaultValue('twig')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
