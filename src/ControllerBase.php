<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;

abstract class ControllerBase implements ControllerInterface
{
    private RequestInterface $request;

    private Route $route;

    private ResponseBuilderInterface $responseBuilder;

    /**
     * {@inheritDoc}
     */
    public function run(
        RequestInterface $request,
        Route $route,
        ResponseBuilderInterface $responseBuilder
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

        return $this->responseBuilder->build($this->route);
    }

    /**
     * Default implementation.
     *
     * @return void
     */
    protected function executeHookPre()
    {
    }

    /**
     * @return void
     */
    abstract protected function execute();

    /**
     * Default implementation.
     *
     * @return void
     */
    protected function executeHookPost()
    {
    }

    /**
     * Default implementation.
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
     * @return mixed
     *
     * @throws VcException
     */
    protected function getArgument(string $name)
    {
        return $this->route->getArgument($name);
    }

    protected function getRoute(): Route
    {
        return $this->route;
    }

    protected function getResponseBuilder(): ResponseBuilderInterface
    {
        return $this->responseBuilder;
    }

    /**
     * @param {string:mixed} $arguments
     *
     * @throws ForwardException Always thrown.
     * @throws VcException
     */
    protected function forward(string $module, string $action, array $arguments = []): void
    {
        $address = new ControllerAddress($module, $action);
        $route   = new Route($address, $arguments);

        throw new ForwardException($route);
    }

    /**
     * @throws RedirectException Always thrown.
     */
    protected function redirectToUrl(string $url, int $httpStatusCode): void
    {
        throw new RedirectException($url, $httpStatusCode);
    }
}
