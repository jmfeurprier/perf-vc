<?php

namespace perf\Vc\Templating;

/**
 * Template interface.
 *
 */
interface TemplateInterface
{

    /**
     * Builds the template content into a string.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function fetch();
}
