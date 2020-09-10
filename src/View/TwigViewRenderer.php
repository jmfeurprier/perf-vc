<?php

namespace perf\Vc\View;

use perf\Vc\Exception\VcException;
use Twig\Cache\CacheInterface as TwigCacheInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\Error as TwigError;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

class TwigViewRenderer implements ViewRendererInterface
{
    private TwigEnvironment $environment;

    /**
     * @param string               $viewFilesBasePath
     * @param TwigCacheInterface   $cache
     * @param array                $options
     * @param ExtensionInterface[] $extensions
     */
    public function __construct(
        string $viewFilesBasePath,
        TwigCacheInterface $cache,
        array $options = [],
        array $extensions = []
    ) {
        $viewFilesBasePath = rtrim($viewFilesBasePath, '\\/');

        $options['cache']            = $cache;
        $options['strict_variables'] = true;

        $this->environment = new TwigEnvironment(
            new FilesystemLoader(
                [
                    $viewFilesBasePath,
                ]
            ),
            $options
        );

        foreach ($extensions as $extension) {
            $this->environment->addExtension($extension);
        }
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
