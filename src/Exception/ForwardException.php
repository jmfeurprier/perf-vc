<?php

namespace perf\Vc\Exception;

class ForwardException extends VcException
{
    private string $module;

    private string $action;

    private array $arguments;

    public function __construct(string $module, string $action, array $arguments)
    {
        parent::__construct();

        $this->module    = $module;
        $this->action    = $action;
        $this->arguments = $arguments;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}
