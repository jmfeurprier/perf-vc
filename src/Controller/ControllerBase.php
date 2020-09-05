<?php

namespace perf\Vc\Controller;

use DomainException;
use Exception;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilder;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouteInterface;

abstract class ControllerBase implements ControllerInterface
{
    private RequestInterface $request;

    private RouteInterface $route;

    private ResponseBuilderInterface $responseBuilder;

    public function run(
        RequestInterface $request,
        RouteInterface $route,
        ResponseBuilder $responseBuilder
    ): ResponseInterface {
        $this->request         = $request;
        $this->route           = $route;
        $this->responseBuilder = $responseBuilder;

        try {
            $this->executeHookPre();
            $this->execute();
            $this->executeHookPost();
        } catch (Exception $e) {
            return $this->onExecutionException($e);
        }

        return $this->responseBuilder->build($route);
    }

    protected function executeHookPre(): void
    {
    }

    abstract protected function execute(): void;

    protected function executeHookPost(): void
    {
    }

    /**
     * @param Exception $e
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    protected function onExecutionException(Exception $e): ResponseInterface
    {
        throw $e;
    }

    protected function getRequest(): RequestInterface
    {
        return $this->request;
    }

    protected function hasArgument(string $name): bool
    {
        return $this->route->hasArgument($name);
    }

    /**
     *
     * Helper method.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws DomainException
     */
    protected function getArgument(string $name)
    {
        return $this->route->getArgument($name);
    }

    protected function getRoute(): RouteInterface
    {
        return $this->route;
    }

    protected function getResponseBuilder(): ResponseBuilderInterface
    {
        return $this->responseBuilder;
    }

    /**
     * @param string $module
     * @param string $action
     * @param {string:mixed} $arguments
     *
     * @return void
     *
     * @throws ForwardException Always thrown.
     */
    protected function forward(string $module, string $action, array $arguments = []): void
    {
        $address = new ControllerAddress($module, $action);
        $route   = new Route($address, $arguments);

        throw new ForwardException($route);
    }

    /**
     *
     * Helper method.
     *
     * @param string $url
     * @param int    $httpStatusCode
     *
     * @return void
     *
     * @throws RedirectException Always thrown.
     */
    protected function redirectToUrl(string $url, int $httpStatusCode): void
    {
        throw new RedirectException($url, $httpStatusCode);
    }

    protected function render(array $vars = []): void
    {
        $this->responseBuilder->renderTemplate(
            $this->route,
            $vars
        );
    }
}
