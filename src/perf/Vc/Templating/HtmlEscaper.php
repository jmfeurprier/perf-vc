<?php

namespace perf\Vc\Templating;

/**
 *
 *
 */
class HtmlEscaper implements EscaperInterface
{

    const CHARSET_DEFAULT = 'UTF-8';

    /**
     *
     *
     * @var string
     */
    private $charset;

    /**
     * Constructor.
     *
     * @param string $charset
     */
    public function __construct($charset = self::CHARSET_DEFAULT)
    {
        $this->charset = $charset;
    }

    /**
     *
     *
     * @param string $content
     * @return string
     */
    public function escape($content)
    {
        return htmlspecialchars($content, ENT_QUOTES, $this->charset, true);
    }
}