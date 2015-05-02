<?php

namespace perf\Vc;

/**
 * Response factory.
 *
 */
class ResponseFactory
{

    /**
     * Creates a new response.
     *
     * @return void
     */
    public function create()
    {
        return new Response();
    }
}
