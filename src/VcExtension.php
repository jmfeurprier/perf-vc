<?php

namespace perf\Vc;

use perf\Source\LocalFileSource;
use perf\Vc\Routing\RouterInterface;
use perf\Vc\Routing\RoutingRuleImporterInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class VcExtension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $containerBuilder)
    {
        $configuration = new VcConfiguration();
        $processor     = new Processor();
        $config        = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $containerBuilder,
            new FileLocator(__DIR__ . '/../config')
        );

        $loader->load('services.yml');

        foreach ($config as $key => $value) {
            $containerBuilder->setParameter("perf.vc.{$key}", $value);
        }

        if ($containerBuilder->hasDefinition(RouterInterface::class)) {
            $routingRules = $containerBuilder->get(RoutingRuleImporterInterface::class)
                ->import(
                    LocalFileSource::create($config['routing_rules_file_path'])
                )
            ;

            $containerBuilder->getDefinition(RouterInterface::class)
                ->setArgument('$routingRules', $routingRules)
            ;
        }
    }

    public function getNamespace()
    {
        return '';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getAlias()
    {
        return 'perf.vc';
    }
}
