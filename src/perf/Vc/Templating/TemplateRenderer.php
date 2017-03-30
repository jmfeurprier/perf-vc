<?php

namespace perf\Vc\Templating;

use perf\Source\Source;
use perf\Source\StringSource;

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
     *
     *
     * @var PluginStack
     */
    private $plugins;

    /**
     * Constructor.
     *
     * @param EscaperInterface         $escaper
     * @param TemplateLocatorInterface $templateLocator
     * @param PluginInterface[]        $plugins
     */
    public function __construct(
        EscaperInterface $escaper,
        TemplateLocatorInterface $templateLocator,
        array $plugins = array()
    ) {
        $this->escaper          = $escaper;
        $this->templateLocator  = $templateLocator;
        $this->plugins          = new PluginStack($plugins);
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

        $template = new Template($this->escaper, $this, $this->plugins, $templatePath, $vars);

        return StringSource::create($template->fetch());
    }
}
