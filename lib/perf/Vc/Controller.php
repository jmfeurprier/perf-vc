<?php

namespace perf\Vc;

/**
 * Controller.
 *
 */
abstract class Controller
{

    /**
     * Current route.
     *
     * @var Routing\Route
     */
    private $route;

    /**
     *
     *
     * @var Request
     */
    private $request;

    /**
     *
     *
     * @var View
     */
    private $view;

    /**
     *
     *
     * @return Response
     */
    private $response;

    /**
     *
     *
     * @var bool
     */
    private $render = true;

    /**
     *
     *
     * @param Routing\Route $route
     * @return Controller Fluent return.
     */
    final public function setRoute(Routing\Route $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     *
     *
     * @param Request $request
     * @return Controller Fluent return.
     */
    final public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Sets the view.
     *
     * @param View $view View.
     * @return Controller Fluent return.
     */
    final public function setView(View $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     *
     *
     * @param Response $response
     * @return Controller Fluent return.
     */
    final public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     *
     *
     * @return void
     */
    final public function run()
    {
        $this->executeHookPre();
        $this->execute();
        $this->executeHookPost();

        if (!$this->render) {
            return;
        }

        $viewContent = $this->getView()->fetch();

        $this->getResponse()->setContent($viewContent);
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
     * Helper method.
     *
     * @return Request
     */
    final protected function getRequest()
    {
        return $this->request;
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    final protected function getModule()
    {
        return $this->route->getModule();
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    final protected function getAction()
    {
        return $this->route->getAction();
    }

    /**
     *
     * Helper method.
     *
     * @param string $parameter
     * @return mixed
     * @throws \DomainException
     */
    final protected function getParameter($parameter)
    {
        $parameters = $this->route->getParameters();

        if (array_key_exists($parameter, $parameters)) {
            return $parameters[$parameter];
        }

        throw new \DomainException("Parameter {$parameter} not found.");
    }

    /**
     *
     * Helper method.
     *
     * @param string $parameter
     * @return bool
     */
    final protected function hasParameter($parameter)
    {
        $parameters = $this->route->getParameters();

        return array_key_exists($parameter, $parameters);
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
    final protected function forward($module, $action, array $parameters = array())
    {
        $route = new Routing\Route($module, $action, $parameters);

        throw new ForwardException($route);
    }

    /**
     *
     *
     * @return View
     */
    final protected function getView()
    {
        return $this->view;
    }

    /**
     *
     *
     * @return void
     */
    final protected function noRender()
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
    final protected function redirectToUrl($url, $httpStatusCode)
    {
        throw new RedirectException($url, $httpStatusCode);
    }

    /**
     *
     *
     * @return Response
     */
    final public function getResponse()
    {
        return $this->response;
    }
}
