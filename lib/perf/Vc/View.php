<?php

namespace perf\Vc;

/**
 * View.
 *
 * Note: the inheritance between this class and ViewBase is to prevent access
 * to private properties by end-user view files.
 *
 */
class View extends ViewBase
{

    /**
     *
     *
     * @return void
     * @throws \Exception
     */
    protected function includeViewFile()
    {
        if (!is_readable($this->getViewPath())) {
            throw new \RuntimeException("Expected view file not found ({$this->getViewPath()}).");
        }

        require($this->getViewPath());
    }
}
