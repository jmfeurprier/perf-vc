<?php

namespace perf\Vc;

/**
 * View.
 *
 */
interface ViewInterface
{

    /**
     * Builds the view content into a string.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function fetch();
}
