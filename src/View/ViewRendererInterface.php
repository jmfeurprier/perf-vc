<?php

namespace perf\Vc\View;

use perf\Vc\Exception\VcException;

interface ViewRendererInterface
{
    /**
     * @param array<string, mixed> $vars
     *
     * @throws VcException
     */
    public function render(string $viewPath, array $vars): string;
}
