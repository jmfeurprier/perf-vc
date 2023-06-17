<?php

namespace perf\Vc\Exception;

class ForwardException extends VcException
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private readonly string $module,
        private readonly string $action,
        private readonly array $arguments
    ) {
        parent::__construct();
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
