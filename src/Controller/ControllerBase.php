<?php

namespace perf\Vc\Controller;

use Exception;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteArgumentNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilder;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
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
        } catch (RedirectException $e) {
            throw $e;
        } catch (ForwardException $e) {
            throw $e;
        } catch (Exception $e) {
            return $this->onExecutionException($e);
        }

        return $this->responseBuilder->build($route);
    }

    /**
     * @return void
     *
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
     * @throws Exception
     */
    protected function executeHookPre(): void
    {
    }

    /**
     * @return void
     *
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
     * @throws Exception
     */
    abstract protected function execute(): void;

    /**
     * @return void
     *
     * @throws RedirectException
     * @throws ForwardException
     * @throws VcException
     * @throws Exception
     */
    protected function executeHookPost(): void
    {
    }

    /**
     * @param Exception $e
     *
     * @return ResponseInterface
     *
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

    protected function hasArgument(string $name): bool
    {
        return $this->route->hasArgument($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws RouteArgumentNotFoundException
     */
    protected function getArgument(string $name)
    {
        return $this->route->getArgument($name);
    }

    protected function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * @param string $module
     * @param string $action
     * @param array  $arguments
     *
     * @return void
     *
     * @throws ForwardException Always thrown.
     */
    protected function forward(string $module, string $action, array $arguments = []): void
    {
        throw new ForwardException($module, $action, $arguments);
    }

    /**
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

    protected function setHttpStatusCode(int $code): void
    {
        $this->responseBuilder->setHttpStatusCode($code);
    }

    /**
     * @param array $vars
     *
     * @return void
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
