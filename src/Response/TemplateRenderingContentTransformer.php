<?php

namespace perf\Vc\Response;

use perf\Vc\Routing\RouteInterface;
use perf\Vc\View\ViewRendererInterface;

class TemplateRenderingContentTransformer implements ContentTransformerInterface
{
    private ViewRendererInterface $templateRenderer;

    /**
     * @var {string:mixed}
     */
    private array $settings = [
        'templating' => true,
    ];

    /**
     * @param \perf\Vc\View\ViewRendererInterface $templateRenderer
     * @param {string:mixed}            $settings
     */
    public function __construct(
        ViewRendererInterface $templateRenderer,
        array $settings = []
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->settings         = array_replace($this->settings, $settings);
    }

    /**
     *
     *
     * @param RouteInterface $route
     * @param mixed          $content
     * @param {string:mixed} $settings
     * @param {string:mixed} $vars
     *
     * @return mixed
     */
    public function transform(RouteInterface $route, $content, array $settings, array $vars)
    {
        $settings = array_replace($this->settings, $settings);

        if (!$settings['templating']) {
            return $content;
        }

        return $this->templateRenderer->render($route, $vars)->getContent();
    }
}
