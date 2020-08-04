<?php

namespace perf\Vc\Redirection;

use InvalidArgumentException;

interface HttpProtocol
{
    /**
     * @param int $httpStatusCode
     *
     * @return HttpStatus
     *
     * @throws InvalidArgumentException
     */
    public function getStatus($httpStatusCode);
}
