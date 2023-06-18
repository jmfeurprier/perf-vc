<?php

namespace perf\Vc\Redirection;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteHasNoPathException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

readonly class RouteRedirection implements RedirectionInterface
{
    use PathToUrlTrait;

    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private ControllerAddress $address,
        private array $arguments,
        private int $httpStatusCode
    ) {
    }

    public function getUrl(
        RequestInterface $request,
        RouterInterface $router
    ): string {
        $route = $router->tryGetByAddress($this->address, $this->arguments);

        if (null === $route) {
            throw new RouteNotFoundException();
        }

        $path = $route->getPath();

        if (null === $path) {
            throw new RouteHasNoPathException();
        }

        return $this->getUrlFromPath($request, $path);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
