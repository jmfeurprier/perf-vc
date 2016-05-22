<?php

namespace perf\Vc;

/**
 * Response factory.
 * Default implementation.
 *
 */
class ResponseFactory implements ResponseFactoryInterface
{

    /**
     * Creates a new response.
     *
     * @return Response
     */
    public function getResponse()
    {
        return new Response();
    }
}
