<?php

namespace perf\Vc\DependencyInjection;

use Exception;
use perf\Source\LocalFileSource;
use perf\Source\SourceInterface;
use perf\Vc\Controller\ControllerRepositoryInterface;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Routing\RouterInterface;
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
use TypeError;

class VcExtension implements ExtensionInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $config;

    private ContainerBuilder $containerBuilder;

    /**
     * @throws Exception
     */
    public function load(
        array $configs,
        ContainerBuilder $container
    ): void {
        $this->init($configs, $container);

        $this->loadServiceDefinitions();
        $this->configureControllerRepository();
        $this->configureViewLocator();
        $this->configureTwigViewRenderer();
        $this->configureRouter();
        $this->configureResponseBuilderFactory();
    }

    /**
     * @throws Exception
     */
    private function loadServiceDefinitions(): void
    {
        $loader = new YamlFileLoader(
            $this->containerBuilder,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.yaml');
    }

    /**
     * @param array<array<mixed>> $configs
     */
    private function init(
        array $configs,
        ContainerBuilder $containerBuilder
    ): void {
        $configuration          = new VcConfiguration();
        $processor              = new Processor();
        $this->config           = $processor->processConfiguration($configuration, $configs);
        $this->containerBuilder = $containerBuilder;
    }

    private function configureControllerRepository(): void
    {
        $this->setDefinitionArgument(
            ControllerRepositoryInterface::class,
            '$controllersNamespace',
            $this->config['controllers_namespace']
        );
    }

    private function configureViewLocator(): void
    {
        $this->setDefinitionArgument(
            ViewLocatorInterface::class,
            '$viewFilesExtension',
            $this->config['view_files_extension']
        );
    }

    private function configureTwigViewRenderer(): void
    {
        $this->setDefinitionArgument(
            ViewRendererInterface::class,
            '$viewFilesBasePath',
            $this->config['view_files_base_path']
        );

        if (!empty($this->config['twig_environment_options'])) {
            $this->setDefinitionArgument(
                ViewRendererInterface::class,
                '$options',
                $this->config['twig_environment_options']
            );
        }

        if (!empty($this->config['twig_extensions'])) {
            if (!is_array($this->config['twig_extensions'])) {
                throw new TypeError();
            }

            $services = [];

            foreach ($this->config['twig_extensions'] as $extensionServiceId) {
                if (!is_string($extensionServiceId)) {
                    throw new TypeError();
                }

                $services[] = new Reference($extensionServiceId);
            }

            $this->setDefinitionArgument(
                ViewRendererInterface::class,
                '$extensions',
                $services
            );
        }
    }

    private function configureRouter(): void
    {
        $definition = new Definition(
            SourceInterface::class,
            [
                '$path' => $this->config['routing_rules_file_path'],
            ]
        );
        $definition->setFactory(
            [
                LocalFileSource::class,
                'create',
            ]
        );
        $this->containerBuilder->setDefinition('perf_vc.routing_rules_source', $definition);

        $this->setDefinitionArgument(
            RouterInterface::class,
            '$routingRules',
            new Expression(
                'service("perf\\\\Vc\\\\Routing\\\\RoutingRuleImporterInterface")' .
                '.import(service("perf_vc.routing_rules_source"))'
            )
        );
    }

    private function configureResponseBuilderFactory(): void
    {
        $this->setDefinitionArgument(ResponseBuilderFactoryInterface::class, '$vars', $this->config['view_vars']);
    }

    private function setDefinitionArgument(
        string $serviceId,
        string $argument,
        mixed $value
    ): void {
        $this->containerBuilder->getDefinition($serviceId)->setArgument($argument, $value);
    }

    public function getNamespace(): string
    {
        return '';
    }

    public function getXsdValidationBasePath(): bool
    {
        return false;
    }

    public function getAlias(): string
    {
        return 'perf_vc';
    }
}
