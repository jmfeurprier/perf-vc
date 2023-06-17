<?php

namespace perf\Vc\Redirection;

use perf\Vc\Controller\ControllerAddress;
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
        $path  = $route->getPath();

        return $this->getUrlFromPath($request, $path);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
