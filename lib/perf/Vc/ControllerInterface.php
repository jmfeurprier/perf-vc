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
     * @param Context $context
     * @return Response
     */
    public function run(Context $context);
}
