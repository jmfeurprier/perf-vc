<?php

namespace perf\Vc\Templating;

use perf\Source\Source;
use perf\Source\StringSource;
use perf\Vc\Routing\Route;
use perf\Vc\Templating\EscaperInterface;
use perf\Vc\Templating\TemplateLocatorInterface;
use perf\Vc\Templating\TemplateRendererInterface;

/**
 * Template renderer.
 */
class TemplateRenderer implements TemplateRendererInterface
{

    /**
     * Escaper.
     *
     * @var EscaperInterface
     */
    private $escaper;

    /**
     *
     *
     * @var TemplateLocatorInterface
     */
    private $templateLocator;

    /**
     * Constructor.
     *
     * @param EscaperInterface         $escaper
     * @param TemplateLocatorInterface $templateLocator
     */
    public function __construct(EscaperInterface $escaper, TemplateLocatorInterface $templateLocator)
    {
        $this->escaper          = $escaper;
        $this->templateLocator  = $templateLocator;
    }

    /**
     *
     *
     * @param Route          $route
     * @param {string:mixed} $vars
     * @return Source
     */
    public function render(Route $route, array $vars)
    {
        $templatePath = $this->templateLocator->locate($route);

        $template = new Template($this->escaper, $templatePath, $vars);

        return StringSource::create($template->fetch());
    }
}
