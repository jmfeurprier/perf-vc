<?php

namespace perf\Vc\Controller;

use Exception;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteArgumentNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\RouteArgumentCollection;
use perf\Vc\Routing\RouteInterface;

abstract class ControllerBase implements ControllerInterface
{
    private RequestInterface $request;

    private RouteInterface $route;

    private ResponseBuilderInterface $responseBuilder;

    public function run(
        RequestInterface $request,
        RouteInterface $route,
        ResponseBuilderInterface $responseBuilder
    ): ResponseInterface {
        $this->request         = $request;
        $this->route           = $route;
        $this->responseBuilder = $responseBuilder;

        try {
            $this->executeHookPre();
            $this->execute();
            $this->executeHookPost();
        } catch (RedirectException | ForwardException $e) {
            throw $e;
        } catch (Exception $e) {
            return $this->onExecutionException($e);
        }

        return $this->responseBuilder->build($route);
    }

    /**
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
     * @throws Exception
     */
    protected function executeHookPre(): void
    {
    }

    /**
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
     * @throws Exception
     */
    abstract protected function execute(): void;

    /**
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
     * @throws Exception
     */
    protected function executeHookPost(): void
    {
    }

    /**
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
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

    protected function arguments(): RouteArgumentCollection
    {
        return $this->route->getArguments();
    }

    protected function hasArgument(string $name): bool
    {
        return $this->arguments()->has($name);
    }

    /**
     * @throws RouteArgumentNotFoundException
     */
    protected function getArgument(string $name): mixed
    {
        return $this->route->getArguments()->get($name);
    }

    protected function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @throws ForwardException Always thrown.
     */
    protected function forward(
        string $module,
        string $action,
        array $arguments = []
    ): never {
        throw new ForwardException($module, $action, $arguments);
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @throws RedirectException Always thrown.
     */
    protected function redirectToRoute(
        string $module,
        string $action,
        array $arguments,
        int $httpStatusCode
    ): never {
        throw RedirectException::createFromRoute($module, $action, $arguments, $httpStatusCode);
    }

    /**
     * @throws RedirectException Always thrown.
     */
    protected function redirectToPath(
        string $path,
        int $httpStatusCode
    ): never {
        throw RedirectException::createFromPath($path, $httpStatusCode);
    }

    /**
     * @throws RedirectException Always thrown.
     */
    protected function redirectToUrl(
        string $url,
        int $httpStatusCode
    ): never {
        throw RedirectException::createFromUrl($url, $httpStatusCode);
    }

    protected function setHttpStatusCode(int $code): void
    {
        $this->responseBuilder->setHttpStatusCode($code);
    }

    /**
     * @param array<string, mixed> $vars
     *
     * @throws VcException
     */
    protected function render(array $vars = []): void
    {
        $this->responseBuilder->renderTemplate(
            $this->route,
            $vars
        );
    }

    protected function getResponseBuilder(): ResponseBuilderInterface
    {
        return $this->responseBuilder;
    }
}
