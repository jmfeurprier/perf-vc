<?php

namespace perf\Vc\Redirection;

/**
 *
 *
 */
class HttpStatus
{

    /**
     * HTTP version.
     *
     * @var string
     */
    private $httpVersion;

    /**
     * HTTP status code.
     *
     * @var int
     */
    private $code;

    /**
     * HTTP status reason.
     *
     * @var string
     */
    private $reason;

    /**
     * Constructor.
     *
     * @param string $httpVersion
     * @param int    $code
     * @param string $reason
     */
    public function __construct($httpVersion, $code, $reason)
    {
        $this->httpVersion = $httpVersion;
        $this->code        = $code;
        $this->reason      = $reason;
    }

    /**
     *
     *
     * @return string
     */
    public function toHeader()
    {
        return "HTTP/{$this->httpVersion} {$this->code} {$this->reason}";
    }
}
