<?php

namespace perf\Vc\View;

use Twig\Cache\CacheInterface as TwigCacheInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

class TwigViewRenderer implements ViewRendererInterface
{
    private TwigEnvironment $environment;

    public function __construct(
        string $viewsBasePath,
        TwigCacheInterface $cache,
        array $twigOptions
    ) {
        $viewsBasePath = rtrim($viewsBasePath, '\\/');

        $twigOptions['cache']            = $cache;
        $twigOptions['strict_variables'] = true;

        $this->environment = new TwigEnvironment(
            new FilesystemLoader(
                [
                    $viewsBasePath,
                ]
            ),
            $twigOptions
        );
    }

    public function render(string $viewPath, array $vars): string
    {
        return $this->environment->render($viewPath, $vars);
    }
}
