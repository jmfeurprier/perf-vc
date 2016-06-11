<?php

namespace perf\Vc;

use perf\Vc\Routing\Address;
use perf\Vc\Routing\Route;

/**
 * Controller.
 *
 */
abstract class ControllerBase implements ControllerInterface
{

    /**
     *
     *
     * @return Response
     */
    private $response;

    /**
     * View factory.
     *
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     *
     *
     * @var bool
     */
    private $render = true;

    /**
     * Current context.
     *
     * @var Context
     */
    private $context;

    /**
     *
     *
     * @var ViewInterface
     */
    private $view;

    /**
     *
     *
     * @param Response $response
     * @return void
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Sets the view factory.
     *
     * @param ViewFactoryInterface $factory View factory.
     * @return void
     */
    public function setViewFactory(ViewFactoryInterface $factory)
    {
        $this->viewFactory = $factory;
    }

    /**
     *
     *
     * @param Context $context
     * @return Response
     */
    public function run(Context $context)
    {
        $this->context = $context;

        $this->executeHookPre();
        $this->execute();
        $this->executeHookPost();

        $response = $this->getResponse();

        if ($this->render) {
            $viewContent = $this->getView()->fetch();

            $response->setContent($viewContent);
        }

        return $response;
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
    protected function getRequest()
    {
        return $this->context->getRequest();
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getModule()
    {
        return $this->getAddress()->getModule();
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getAction()
    {
        return $this->getAddress()->getAction();
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getAddress()
    {
        return $this->getRoute()->getAddress();
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
        return $this->getRoute()->hasParameter($parameter);
    }

    /**
     *
     * Helper method.
     *
     * @param string $parameter
     * @return mixed
     * @throws \DomainException
     */
    protected function getParameter($parameter)
    {
        return $this->getRoute()->getParameter($parameter);
    }

    /**
     *
     * Helper method.
     *
     * @return string
     */
    protected function getRoute()
    {
        return $this->context->getRoute();
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
        if (!$this->view) {
            $this->view = $this->viewFactory->getView($this->getRoute());

            $this->configureView($this->view);
        }

        return $this->view;
    }

    /**
     *
     * Hook.
     * Default implementation.
     *
     * @param ViewInterface $view
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureView(ViewInterface $view)
    {
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
