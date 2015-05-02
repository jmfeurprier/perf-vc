<?php

namespace perf\Vc\Redirect;

/**
 *
 *
 */
interface HttpProtocol
{

    /**
     *
     *
     * @param int $httpStatusCode
     * @return HttpStatus
     * @throws InvalidArgumentException
     */
    public function getStatus($httpStatusCode);
}
