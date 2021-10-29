<?php

namespace perf\Vc\Exception;

use Exception;
use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Redirection\PathRedirection;
use perf\Vc\Redirection\RedirectionInterface;
use perf\Vc\Redirection\RouteRedirection;
use perf\Vc\Redirection\UrlRedirection;

class RedirectException extends Exception
{
    private RedirectionInterface $redirection;

    public static function createFromRoute(
        string $module,
        string $action,
        array $arguments,
        int $httpStatusCode
    ): self {
        return new self(
            new RouteRedirection(
                new ControllerAddress(
                    $module,
                    $action
                ),
                $arguments,
                $httpStatusCode
            )
        );
    }

    public static function createFromPath(
        string $path,
        int $httpStatusCode
    ): self {
        return new self(
            new PathRedirection(
                $path,
                $httpStatusCode
            )
        );
    }

    public static function createFromUrl(
        string $url,
        int $httpStatusCode
    ): self {
        return new self(
            new UrlRedirection(
                $url,
                $httpStatusCode
            )
        );
    }

    public function __construct(RedirectionInterface $redirection)
    {
        parent::__construct();

        $this->redirection = $redirection;
    }

    public function getRedirection(): RedirectionInterface
    {
        return $this->redirection;
    }
}
