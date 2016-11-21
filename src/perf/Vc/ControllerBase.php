<?php

namespace perf\Vc;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;

/**
 * Controller.
 *
 */
abstract class ControllerBase implements ControllerInterface
{

    /**
     * Current request.
     * Temporary property.
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Current route.
     * Temporary property.
     *
     * @var Route
     */
    private $route;

    /**
     * Response builder.
     * Temporary property.
     *
     * @return ResponseBuilderInterface
     */
    private $responseBuilder;

    /**
     *
     *
     * @param RequestInterface         $request
     * @param Route                    $route
     * @param ResponseBuilderInterface $responseBuilder
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, Route $route, ResponseBuilderInterface $responseBuilder)
    {
        $this->request         = $request;
        $this->route           = $route;
        $this->responseBuilder = $responseBuilder;

        try {
            $this->executeHookPre();
            $this->execute();
            $this->executeHookPost();
        } catch (\Exception $e) {
            return $this->onExecutionException($e);
        }

        return $this->responseBuilder->build($route);
    }

    /**
     *
     * Default implementation.
     *
     * @return void
     */
    protected function executeHookPre()
    {
    }

    /**
     *
     *
     * @return void
     */
    abstract protected function execute();

    /**
     *
     * Default implementation.
     *
     * @return void
     */
    protected function executeHookPost()
    {
    }

    /**
     *
     * Default implementation.
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    protected function onExecutionException(\Exception $e)
    {
        throw $e;
    }

    /**
     *
     * Helper method.
     *
     * @return RequestInterface
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     *
     * Helper method.
     *
     * @param string $name
     * @return bool
     */
    protected function hasArgument($name)
    {
        return $this->route->hasArgument($name);
    }

    /**
     *
     * Helper method.
     *
     * @param string $name
     * @return mixed
     * @throws \DomainException
     */
    protected function getArgument($name)
    {
        return $this->route->getArgument($name);
    }

    /**
     *
     * Helper method.
     *
     * @return Route
     */
    protected function getRoute()
    {
        return $this->route;
    }

    /**
     *
     * Helper method.
     *
     * @return ResponseBuilderInterface
     */
    protected function getResponseBuilder()
    {
        return $this->responseBuilder;
    }

    /**
     *
     * Helper method.
     *
     * @param string         $module
     * @param string         $action
     * @param {string:mixed} $arguments
     * @return void
     * @throws ForwardException Always thrown.
     */
    protected function forward($module, $action, array $arguments = array())
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
     * @param int $httpStatusCode
     * @return void
     * @throws RedirectException Always thrown.
     */
    protected function redirectToUrl($url, $httpStatusCode)
    {
        throw new RedirectException($url, $httpStatusCode);
    }
}
