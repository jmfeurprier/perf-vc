<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

interface ControllerInterface
{
    /**
     * @param Route $route
     *
     * @return void
     */
    public function setRoute(Route $route);

    /**
     * @param Request $request
     *
     * @return void
     */
    public function setRequest(Request $request);

    /**
     * @param ViewInterface $view
     *
     * @return void
     */
    public function setView(ViewInterface $view);

    /**
     * @param Response $response
     *
     * @return void
     */
    public function setResponse(Response $response);

    /**
     * @return void
     */
    public function run();

    /**
     * @return Response
     */
    public function getResponse();
}
