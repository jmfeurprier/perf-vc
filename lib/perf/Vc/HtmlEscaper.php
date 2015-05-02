<?php

namespace perf\Vc;

/**
 *
 *
 */
class HtmlEscaper implements Escaper
{

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
     * @return void
     */
    public function __construct($charset = 'UTF-8')
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
