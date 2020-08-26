<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\VcException;

interface RequestPopulatorInterface
{
    /**
     * Returns a new HTTP request instance, populated with global values.
     *
     * @return RequestInterface
     *
     * @throws VcException
     */
    public function populate(): RequestInterface;
}
