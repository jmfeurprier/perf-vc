<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;

/**
 * MVC router.
 *
 */
interface RouterInterface
{

    /**
     * Attempts to match provided request against routing rules.
     *
     * @param Request $request Request.
     * @return null|Route
     */
    public function tryGetRoute(Request $request);
}
