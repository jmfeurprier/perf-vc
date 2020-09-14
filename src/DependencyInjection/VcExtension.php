<?php

namespace perf\Vc\DependencyInjection;

use perf\Source\LocalFileSource;
use perf\Source\SourceInterface;
use perf\Vc\Controller\ControllerFactory;
use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Response\ResponseBuilderFactory;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Routing\Router;
use perf\Vc\Routing\RouterInterface;
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
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

class VcExtension implements ExtensionInterface
{
    private array $config;

    private ContainerBuilder $containerBuilder;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $containerBuilder)
    {
        $this->init($configs, $containerBuilder);

        $loader = new YamlFileLoader(
            $this->containerBuilder,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.yml');

        $this->configureControllerFactory();
        $this->configureViewLocator();
        $this->configureTwigViewRenderer();
        $this->configureRouter();
        $this->configureResponseBuilderFactory();
    }

    private function init(array $configs, ContainerBuilder $containerBuilder)
    {
        $configuration          = new VcConfiguration();
        $processor              = new Processor();
        $this->config           = $processor->processConfiguration($configuration, $configs);
        $this->containerBuilder = $containerBuilder;
    }

    private function configureControllerFactory(): void
    {
        $definition = $this->containerBuilder->getDefinition(ControllerFactoryInterface::class);

        if ($definition->getClass() === ControllerFactory::class) {
            $definition->setArgument('$controllersNamespace', $this->config['controllers_namespace']);
        }
    }

    private function configureViewLocator(): void
    {
        $definition = $this->containerBuilder->getDefinition(ViewLocatorInterface::class);

        if ($definition->getClass() === ViewLocator::class) {
            $definition->setArgument('$viewFilesExtension', $this->config['view_files_extension']);
        }
    }

    private function configureTwigViewRenderer(): void
    {
        $definition = $this->containerBuilder->getDefinition(ViewRendererInterface::class);

        if ($definition->getClass() === TwigViewRenderer::class) {
            $definition->setArgument('$viewFilesBasePath', $this->config['view_files_base_path']);

            if (!empty($this->config['twig_extensions'])) {
                $services = [];

                foreach ($this->config['twig_extensions'] as $extensionServiceId) {
                    $services[] = new Reference($extensionServiceId);
                }

                $definition->setArgument('$extensions', $services);
            }
        }
    }

    private function configureRouter(): void
    {
        $definition = new Definition(SourceInterface::class);
        $definition
            ->setFactory([LocalFileSource::class, 'create'])
            ->setArgument('$path', $this->config['routing_rules_file_path'])
        ;

        $this->containerBuilder->setDefinition('perf_vc.routing_rules_source', $definition);

        $definition = $this->containerBuilder->getDefinition(RouterInterface::class);

        if ($definition->getClass() === Router::class) {
            $definition->setArgument(
                '$routingRules',
                new Expression(
                    'service("perf\\\\Vc\\\\Routing\\\\RoutingRuleImporterInterface").import(service("perf_vc.routing_rules_source"))'
                )
            );
        }
    }

    private function configureResponseBuilderFactory(): void
    {
        $definition = $this->containerBuilder->getDefinition(ResponseBuilderFactoryInterface::class);

        if ($definition->getClass() === ResponseBuilderFactory::class) {
            $definition->setArgument('$vars', $this->config['view_vars']);
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
