<?php

namespace perf\Vc\View;

use perf\Source\SourceInterface;

interface ViewRendererInterface
{
    public function render(string $viewPath, array $vars): string;
}
