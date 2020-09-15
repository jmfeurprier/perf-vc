<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;

trait PathToUrlTrait
{
    private function getUrlFromPath(RequestInterface $request, string $path): string
    {
        $transport   = $request->getTransport();
        $hostAndPort = $this->getHostAndPort($request);

        return "{$transport}://{$hostAndPort}/{$path}";
    }

    private function getHostAndPort(RequestInterface $request): string
    {
        $transport = $request->getTransport();
        $host      = $request->getHost();
        $port      = $request->getPort();

        if ($this->isDefaultPortForTransport($port, $transport)) {
            return $host;
        }

        return "{$host}:{$port}";
    }

    private function isDefaultPortForTransport(int $port, string $transport): bool
    {
        $defaultPorts = [
            RequestInterface::TRANSPORT_HTTP  => 80,
            RequestInterface::TRANSPORT_HTTPS => 443,
        ];

        return (($defaultPorts[$transport] ?? null) === $port);
    }
}
