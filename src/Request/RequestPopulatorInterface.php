<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\VcException;

interface RequestPopulatorInterface
{
    /**
     * Returns a new HTTP request instance, populated with global values.
     *
     * @throws VcException
     */
    public function populate(): RequestInterface;
}
