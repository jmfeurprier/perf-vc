<?php

namespace perf\Vc\Templating;

/**
 * Template.
 *
 * The inheritance between this class and TemplateBase is to prevent access
 * to private properties by end-user templates.
 */
class Template extends TemplateBase
{

    /**
     *
     *
     * @return void
     * @throws \RuntimeException
     */
    protected function includeTemplate()
    {
        if (!is_readable($this->getPath())) {
            throw new \RuntimeException("Expected template not found ({$this->getPath()}).");
        }

        require($this->getPath());
    }
}
