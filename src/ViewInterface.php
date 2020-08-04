<?php

namespace perf\Vc;

use RuntimeException;

interface ViewInterface
{
    /**
     * Builds the view content into a string.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function fetch();
}
