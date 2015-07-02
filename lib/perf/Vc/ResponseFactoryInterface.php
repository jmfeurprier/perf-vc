<?php

namespace perf\Vc;

/**
 * Response factory.
 *
 */
interface ResponseFactoryInterface
{

    /**
     * Creates a new response.
     *
     * @return Response
     */
    public function getResponse();
}
