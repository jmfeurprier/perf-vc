<?php

namespace perf\Vc;

interface Escaper
{
    /**
     * @param string $content
     *
     * @return string
     */
    public function escape($content);
}
