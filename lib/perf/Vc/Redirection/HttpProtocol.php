<?php

namespace perf\Vc\Redirection;

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
     * @throws \InvalidArgumentException
     */
    public function getStatus($httpStatusCode);
}
