<?php

namespace perf\Vc\Response;

use perf\Vc\Routing\Route;
use perf\Vc\Templating\TemplateRendererInterface;

/**
 * Template rendering content transformer.
 */
class TemplateRenderingContentTransformer implements ContentTransformerInterface
{

    /**
     *
     *
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * Settings.
     *
     * @var {string:mixed}
     */
    private $settings = array(
        'templating' => true,
    );

    /**
     * Constructor.
     *
     * @param TemplateRendererInterface $templateRenderer
     * @param {string:mixed}            $settings
     */
    public function __construct(TemplateRendererInterface $templateRenderer, array $settings = array())
    {
        $this->templateRenderer = $templateRenderer;
        $this->settings         = array_replace($this->settings, $settings);
    }

    /**
     *
     *
     * @param Route          $route
     * @param mixed          $content
     * @param {string:mixed} $settings
     * @param {string:mixed} $vars
     * @return mixed
     */
    public function transform(Route $route, $content, array $settings, array $vars)
    {
        $settings = array_replace($this->settings, $settings);

        if (!$settings['templating']) {
            return $content;
        }

        return $this->templateRenderer->render($route, $vars)->getContent();
    }
}
