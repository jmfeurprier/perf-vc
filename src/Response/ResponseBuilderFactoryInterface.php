<?php

namespace perf\Vc\Response;

interface ResponseBuilderFactoryInterface
{
    public function make(): ResponseBuilder;
}
