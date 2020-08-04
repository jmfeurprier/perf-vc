<?php

namespace perf\Vc;

use DomainException;
use perf\Vc\Routing\Address;
use perf\Vc\Routing\Route;

abstract class ControllerBase implements ControllerInterface
{
    /**
     * @var Route
     */
    private $route;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ViewInterface
     */
    private $view;

    /**
     * @return Response
     */
    private $response;

    /**
     * @var bool
     */
    private $render = true;

    /**
     * @param Route $route
     *
     * @return ControllerBase Fluent return.
     */
    public function setRoute(Route $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @param Request $request
     *
     * @return ControllerBase Fluent return.
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Sets the view.
     *
     * @param ViewInterface $view View.
     *
     * @return ControllerBase Fluent return.
     */
    public function setView(ViewInterface $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param Response $response
     *
     * @return ControllerBase Fluent return.
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->executeHookPre();
        $this->execute();
        $this->executeHookPost();

        if ($this->render) {
            $viewContent = $this->getView()->fetch();

            $this->getResponse()->setContent($viewContent);
        }
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
     * Helper method.
     *
     * @return Request
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getRoute()
    {
        return $this->route;
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getModule()
    {
        return $this->getRoute()->getAddress()->getModule();
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getAction()
    {
        return $this->getRoute()->getAddress()->getAction();
    }

    /**
     *
     * Helper method.
     *
     * @param string $parameter
     * @return mixed
     * @throws DomainException
     */
    protected function getParameter($parameter)
    {
        return $this->route->getParameter($parameter);
    }

    /**
     *
     * Helper method.
     *
     * @param string $parameter
     * @return bool
     */
    protected function hasParameter($parameter)
    {
        return $this->route->hasParameter($parameter);
    }

    /**
     *
     * Helper method.
     *
     * @param string $module
     * @param string $action
     * @param {string:mixed} $parameters
     * @return void
     * @throws ForwardException Always thrown.
     */
    protected function forward($module, $action, array $parameters = array())
    {
        $address = new Address($module, $action);
        $route   = new Route($address, $parameters);

        throw new ForwardException($route);
    }

    /**
     *
     *
     * @return ViewInterface
     */
    protected function getView()
    {
        return $this->view;
    }

    /**
     *
     *
     * @return void
     */
    protected function noRender()
    {
        $this->render = false;
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

    /**
     *
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
