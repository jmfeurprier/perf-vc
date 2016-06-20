<?php

namespace perf\Vc\Templating;

/**
 *
 *
 */
interface EscaperInterface
{

    /**
     *
     *
     * @param string $content
     * @return string
     */
    public function escape($content);
}
