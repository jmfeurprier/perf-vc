<?php

namespace perf\Vc;

use perf\Source\LocalFileSource;
use perf\Source\SourceInterface;
use perf\Vc\Controller\ControllerFactory;
use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Routing\Router;
use perf\Vc\Routing\RouterInterface;
use perf\Vc\Routing\RoutingRuleImporterInterface;
use perf\Vc\View\TwigViewRenderer;
use perf\Vc\View\ViewLocator;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\ExpressionLanguage\Expression;

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

        $definition = $containerBuilder->getDefinition(ControllerFactoryInterface::class);
        if ($definition->getClass() === ControllerFactory::class) {
            $definition->setArgument('$controllersNamespace', $config['controllers_namespace']);
        }

        $definition = $containerBuilder->getDefinition(ViewLocatorInterface::class);
        if ($definition->getClass() === ViewLocator::class) {
            $definition->setArgument('$viewFilesExtension', $config['view_files_extension']);
        }

        $definition = $containerBuilder->getDefinition(ViewRendererInterface::class);
        if ($definition->getClass() === TwigViewRenderer::class) {
            $definition->setArgument('$viewFilesBasePath', $config['view_files_base_path']);
        }

        $definition = new Definition(SourceInterface::class);
        $definition
            ->setFactory([LocalFileSource::class, 'create'])
            ->setArgument('$path', $config['routing_rules_file_path'])
        ;
        $containerBuilder->setDefinition(
            'perf_vc.routing_rules_source',
            $definition
        );

        $definition = $containerBuilder->getDefinition(RouterInterface::class);
        if ($definition->getClass() === Router::class) {
            $definition->setArgument(
                '$routingRules',
                new Expression('service("perf\\\\Vc\\\\Routing\\\\RoutingRuleImporterInterface").import(service("perf_vc.routing_rules_source"))')
            );
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
        return 'perf_vc';
    }
}
