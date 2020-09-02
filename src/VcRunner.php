<?php

namespace perf\Vc;

use Closure;
use perf\Caching\Storage\CachingStorageInterface;
use perf\Caching\Storage\VolatileCachingStorage;
use perf\HttpStatus\HttpStatusRepository;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Source\LocalFileSource;
use perf\Timing\Clock;
use perf\Timing\ClockInterface;
use perf\Vc\Controller\ControllerClassResolver;
use perf\Vc\Controller\ControllerClassResolverInterface;
use perf\Vc\Controller\ControllerFactory;
use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectionHeadersGenerator;
use perf\Vc\Redirection\RedirectionHeadersGeneratorInterface;
use perf\Vc\Redirection\Redirector;
use perf\Vc\Redirection\RedirectorInterface;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Request\RequestPopulator;
use perf\Vc\Request\RequestPopulatorInterface;
use perf\Vc\Response\ResponseBuilderFactory;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Response\ResponseSender;
use perf\Vc\Response\ResponseSenderInterface;
use perf\Vc\Response\Transformation\TransformerRepository;
use perf\Vc\Response\Transformation\TransformerRepositoryInterface;
use perf\Vc\Routing\Router;
use perf\Vc\Routing\RouterInterface;
use perf\Vc\Routing\RoutingRuleImporterInterface;
use perf\Vc\Routing\RoutingRuleMatcher;
use perf\Vc\Routing\RoutingRuleMatcherInterface;
use perf\Vc\Routing\YamlRoutingRuleImporter;
use perf\Vc\View\TwigCache;
use perf\Vc\View\TwigViewRenderer;
use perf\Vc\View\ViewLocator;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Cache\CacheInterface as TwigCacheInterface;

class VcRunner
{
    public const CONTAINER_PARAMETER_CONTROLLER_NAMESPACE    = 'perf.vc.controller_namespace';
    public const CONTAINER_PARAMETER_VIEW_FILES_BASE_PATH    = 'perf.vc.view_files_base_path';
    public const CONTAINER_PARAMETER_VIEW_FILES_EXTENSION    = 'perf.vc.view_files_extension';
    public const CONTAINER_PARAMETER_ROUTING_RULES_FILE_PATH = 'perf.vc.routing_rules_file_path';

    private const PARAMETERS_DEFAULT_VALUE = [
        self::CONTAINER_PARAMETER_VIEW_FILES_EXTENSION => 'twig',
    ];

    private ContainerInterface $container;

    private FrontControllerInterface $frontController;

    private RequestInterface $request;

    private ResponseSenderInterface $responseSender;

    public function run(ContainerInterface $container): void
    {
        $this->init($container);

        $response = $this->frontController->run($this->request);

        $this->responseSender->send($response);
    }

    private function init(ContainerInterface $container): void
    {
        $this->container       = $container;
        $this->frontController = $this->get(FrontControllerInterface::class);
        $this->request         = $this->get(RequestInterface::class);
        $this->responseSender  = $this->get(ResponseSenderInterface::class);
    }

    private function get(string $serviceId): object
    {
        if ($this->container->has($serviceId)) {
            return $this->container->get($serviceId);
        }

        $definition = $this->getServiceDefinition($serviceId);

        return $definition();
    }

    private function getServiceDefinition(string $serviceId): Closure
    {
        $definitions = [
            CachingStorageInterface::class              => (fn() => new VolatileCachingStorage()),
            ClockInterface::class                       => (fn() => new Clock()),
            ControllerClassResolverInterface::class     => (fn() => new ControllerClassResolver()),
            ControllerFactoryInterface::class           => (fn() => new ControllerFactory(
                $this->get(ControllerClassResolverInterface::class),
                $this->getParameter(self::CONTAINER_PARAMETER_CONTROLLER_NAMESPACE),
                $this->container
            )),
            FrontControllerInterface::class             => (fn() => new FrontController(
                $this->get(RouterInterface::class),
                $this->get(ControllerFactoryInterface::class),
                $this->get(ResponseBuilderFactoryInterface::class),
                $this->get(RedirectorInterface::class)
            )),
            HttpStatusRepositoryInterface::class        => (fn() => HttpStatusRepository::createDefault()),
            RedirectionHeadersGeneratorInterface::class => (fn() => new RedirectionHeadersGenerator(
                $this->get(HttpStatusRepositoryInterface::class)
            )),
            RedirectorInterface::class                  => (fn() => new Redirector(
                $this->get(RedirectionHeadersGeneratorInterface::class)
            )),
            RequestInterface::class                     => (fn() => $this->get(
                RequestPopulatorInterface::class
            )->populate()),
            RequestPopulatorInterface::class            => (fn() => RequestPopulator::createDefault()),
            ResponseBuilderFactoryInterface::class      => (fn() => new ResponseBuilderFactory(
                $this->get(HttpStatusRepositoryInterface::class),
                $this->get(ViewLocatorInterface::class),
                $this->get(ViewRendererInterface::class),
                $this->get(TransformerRepositoryInterface::class)
            )),
            ResponseSenderInterface::class              => (fn() => new ResponseSender()),
            RouterInterface::class                      => (fn() => new Router(
                $this->get(RoutingRuleMatcherInterface::class),
                $this->getRoutingRules()
            )),
            RoutingRuleImporterInterface::class         => (fn() => YamlRoutingRuleImporter::createDefault()),
            RoutingRuleMatcherInterface::class          => (fn() => new RoutingRuleMatcher()),
            TransformerRepositoryInterface::class       => (fn() => TransformerRepository::createDefault()),
            TwigCacheInterface::class                   => (fn() => new TwigCache(
                $this->get(CachingStorageInterface::class),
                $this->get(ClockInterface::class)
            )),
            ViewLocatorInterface::class                 => (fn() => new ViewLocator(
                $this->getParameter(self::CONTAINER_PARAMETER_VIEW_FILES_EXTENSION)
            )),
            ViewRendererInterface::class                => (fn() => new TwigViewRenderer(
                $this->getParameter(self::CONTAINER_PARAMETER_VIEW_FILES_BASE_PATH),
                $this->get(TwigCacheInterface::class)
            )),
        ];

        return $definitions[$serviceId];
    }

    private function getRoutingRules(): array
    {
        $routingRuleImporter = $this->get(RoutingRuleImporterInterface::class);

        return $routingRuleImporter->import(
            LocalFileSource::create(
                $this->getParameter(self::CONTAINER_PARAMETER_ROUTING_RULES_FILE_PATH)
            )
        );
    }

    /**
     * @param string $parameterId
     *
     * @return mixed
     *
     * @throws VcException
     */
    private function getParameter(string $parameterId)
    {
        if ($this->container->hasParameter($parameterId)) {
            return $this->container->getParameter($parameterId);
        }

        if (array_key_exists($parameterId, self::PARAMETERS_DEFAULT_VALUE)) {
            return self::PARAMETERS_DEFAULT_VALUE[$parameterId];
        }

        throw new VcException("Container parameter '{$parameterId}' must be defined.");
    }
}
