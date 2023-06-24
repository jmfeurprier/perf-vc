<?php

namespace perf\Vc\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class VcConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('perf_vc');

        // @phpstan-ignore-next-line
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('controllers_namespace')
                    ->info('Namespace of controller classes.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('route_definitions')
                    ->info('Route definitions.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('twig_environment_options')
                    ->info('List of Twig environment options.')
                   ->scalarPrototype()
                    ->end()
                ->end()
                ->arrayNode('twig_extensions')
                    ->info('List of Twig extension classes/services to load.')
                   ->scalarPrototype()
                    ->end()
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
                ->arrayNode('view_vars')
                    ->info('List of variables to inject in every view / template.')
                    ->variablePrototype()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
