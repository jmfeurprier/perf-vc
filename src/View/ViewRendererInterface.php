<?php

namespace perf\Vc\View;

use perf\Vc\Exception\VcException;

interface ViewRendererInterface
{
    /**
     * @param string $viewPath
     * @param array  $vars
     *
     * @return string
     *
     * @throws VcException
     */
    public function render(string $viewPath, array $vars): string;
}
