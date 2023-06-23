<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Exception\RouteArgumentNotFoundException;
use TypeError;

readonly class RouteArgumentCollection
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private array $arguments = []
    ) {
        foreach (array_keys($arguments) as $key) {
            if (!is_string($key)) {
                throw new TypeError('Invalid route argument key type.');
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->arguments;
    }

    /**
     * @throws RouteArgumentNotFoundException
     */
    public function get(string $name): mixed
    {
        if (array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }

        throw new RouteArgumentNotFoundException($name);
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }
}
