<?php

namespace perf\Vc\Templating;

interface EscaperInterface
{
    public function escape(string $content): string;
}
