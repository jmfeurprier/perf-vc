<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Exception\RouteArgumentNotFoundException;

class RouteArgumentCollection
{
    /**
     * @var array<string, mixed>
     */
    private array $arguments = [];

    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        array $arguments = []
    ) {
        foreach ($arguments as $key => $value) {
            $this->addArgument($key, $value);
        }
    }

    private function addArgument(
        string $key,
        mixed $value
    ): void {
        $this->arguments[$key] = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAll(): array
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
