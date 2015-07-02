<?php

namespace perf\Vc\Redirection;

/**
 *
 *
 */
class Http10Protocol implements HttpProtocol
{

    const VERSION = '1.0';

    const STATUS_MULTIPLE_CHOICES  = 300;
    const STATUS_MOVED_PERMANENTLY = 301;
    const STATUS_MOVED_TEMPORARILY = 302;
    const STATUS_NOT_MODIFIED      = 304;

    /**
     * Associative array matching HTTP status codes to HTTP status reasons.
     *
     * @var {int:string}
     */
    private $reasons = array(
        self::STATUS_MULTIPLE_CHOICES   => 'Multiple Choices',
        self::STATUS_MOVED_PERMANENTLY  => 'Moved Permanently',
        self::STATUS_MOVED_TEMPORARILY  => 'Moved Temporarily',
        self::STATUS_NOT_MODIFIED       => 'Not Modified',
    );

    /**
     *
     *
     * @param int $httpStatusCode
     * @return HttpStatus
     * @throws \InvalidArgumentException
     */
    public function getStatus($httpStatusCode)
    {
        if (!array_key_exists($httpStatusCode, $this->reasons)) {
            throw new \InvalidArgumentException("Invalid HTTP status code '{$httpStatusCode}'.");
        }

        $reason = $this->reasons[$httpStatusCode];

        return new HttpStatus(self::VERSION, $httpStatusCode, $reason);
    }
}
