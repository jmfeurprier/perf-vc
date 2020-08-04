<?php

namespace perf\Vc;

use Exception;

/**
 * Exception for redirects.
 */
class RedirectException extends Exception
{
    /**
     * Redirect URL.
     *
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $httpStatusCode;

    /**
     * @param string $url Redirect URL.
     * @param int $httpStatusCode
     */
    public function __construct($url, $httpStatusCode)
    {
        $this->url            = (string) $url;
        $this->httpStatusCode = (int) $httpStatusCode;
    }

    /**
     * Returns the redirect URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}