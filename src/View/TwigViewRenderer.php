<?php

namespace perf\Vc\View;

use perf\Vc\Exception\VcException;
use Twig\Cache\CacheInterface as TwigCacheInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\Error as TwigError;
use Twig\Loader\FilesystemLoader;

class TwigViewRenderer implements ViewRendererInterface
{
    private TwigEnvironment $environment;

    public function __construct(
        string $viewsBasePath,
        TwigCacheInterface $cache,
        array $twigOptions = []
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

    /**
     * {@inheritDoc}
     */
    public function render(string $viewPath, array $vars): string
    {
        try {
            return $this->environment->render($viewPath, $vars);
        } catch (TwigError $e) {
            throw new VcException("Failed rendering twig view at {$viewPath}", 0, $e);
        }
    }
}
