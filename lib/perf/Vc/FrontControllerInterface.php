<?php

namespace perf\Vc;

/**
 *
 *
 */
interface FrontControllerInterface
{

    /**
     * Runs the front controller.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function run(Request $request);
}
