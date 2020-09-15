<?php

namespace perf\Vc\Redirection;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

class RouteRedirection implements RedirectionInterface
{
    use PathToUrlTrait;

    private ControllerAddress $address;

    private array $arguments;

    private int $httpStatusCode;

    public function __construct(ControllerAddress $address, array $arguments, int $httpStatusCode)
    {
        $this->address        = $address;
        $this->arguments      = $arguments;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getUrl(RequestInterface $request, RouterInterface $router): string
    {
        $route = $router->tryGetByAddress($this->address, $this->arguments);
        $path  = $route->getPath();

        return $this->getUrlFromPath($request, $path);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
