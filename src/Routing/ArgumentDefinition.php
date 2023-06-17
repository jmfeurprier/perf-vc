<?php

namespace perf\Vc\Routing;

readonly class ArgumentDefinition
{
    public function __construct(
        private string $name,
        private string $format,
        private mixed $defaultValue
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }
}
