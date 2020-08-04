<?php

namespace perf\Vc\Redirection;

use InvalidArgumentException;

class Http11Protocol implements HttpProtocol
{
    const VERSION = '1.1';

    const STATUS_MOVED_PERMANENTLY  = 301;
    const STATUS_FOUND              = 302;
    const STATUS_SEE_OTHER          = 303;
    const STATUS_TEMPORARY_REDIRECT = 307;

    /**
     * Associative array matching HTTP status codes to HTTP status reasons.
     *
     * @var {int:string}
     */
    private $reasons = array(
        self::STATUS_MOVED_PERMANENTLY  => 'Moved Permanently',
        self::STATUS_FOUND              => 'Found',
        self::STATUS_SEE_OTHER          => 'See Other',
        self::STATUS_TEMPORARY_REDIRECT => 'Temporary Redirect',
    );

    /**
     * @param int $httpStatusCode
     *
     * @return HttpStatus
     *
     * @throws InvalidArgumentException
     */
    public function getStatus($httpStatusCode)
    {
        if (!array_key_exists($httpStatusCode, $this->reasons)) {
            throw new InvalidArgumentException("Invalid HTTP status code '{$httpStatusCode}'.");
        }

        $reason = $this->reasons[$httpStatusCode];

        return new HttpStatus(self::VERSION, $httpStatusCode, $reason);
    }
}
