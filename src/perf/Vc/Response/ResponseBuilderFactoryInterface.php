<?php

namespace perf\Vc\Response;

/**
 * Response builder factory interface.
 *
 */
interface ResponseBuilderFactoryInterface
{

    /**
     *
     *
     * @return ResponseBuilder
     */
    public function create();
}
