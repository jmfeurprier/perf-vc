<?php

namespace perf\Vc;

use perf\Vc\Routing\Address;
use perf\Vc\Routing\Route;

/**
 * Controller.
 *
 */
interface ControllerInterface
{

    /**
     *
     *
     * @param Routing\Route $route
     * @return void
     */
    public function setRoute(Routing\Route $route);

    /**
     *
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request);

    /**
     * Sets the view.
     *
     * @param View $view View.
     * @return void
     */
    public function setView(View $view);

    /**
     *
     *
     * @param Response $response
     * @return void
     */
    public function setResponse(Response $response);

    /**
     *
     *
     * @return void
     */
    public function run();

    /**
     *
     *
     * @return Response
     */
    public function getResponse();
}
