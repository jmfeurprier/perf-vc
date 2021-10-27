<?php

namespace perf\Vc\View;

use perf\Vc\Exception\VcException;

interface ViewRendererInterface
{
    /**
     * @throws VcException
     */
    public function render(string $viewPath, array $vars): string;
}
