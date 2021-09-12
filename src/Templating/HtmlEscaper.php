<?php

namespace perf\Vc\Templating;

class HtmlEscaper implements EscaperInterface
{
    private const CHARSET_DEFAULT = 'UTF-8';

    private string $charset;

    public function __construct(string $charset = self::CHARSET_DEFAULT)
    {
        $this->charset = $charset;
    }

    public function escape(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES, $this->charset, true);
    }
}
