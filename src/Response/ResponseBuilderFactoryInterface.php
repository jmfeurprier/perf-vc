<?php

namespace perf\Vc\Response;

interface ResponseBuilderFactoryInterface
{
    public function create(): ResponseBuilder;
}
