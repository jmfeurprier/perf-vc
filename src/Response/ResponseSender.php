<?php

namespace perf\Vc\Response;

use perf\Source\Exception\SourceException;
use perf\Vc\Exception\VcException;

class ResponseSender implements ResponseSenderInterface
{
    private ResponseInterface $response;

    /**
     * {@inheritDoc}
     */
    public function send(ResponseInterface $response): void
    {
        $this->response = $response;

        $this->sendHeaders();
        $this->sendContent();
    }

    private function sendHeaders(): void
    {
        foreach ($this->response->getHeaders() as $header) {
            $header->send();
        }
    }

    /**
     * @return void
     *
     * @throws VcException
     */
    private function sendContent(): void
    {
        try {
            $this->response->getContent()->send();
        } catch (SourceException $e) {
            throw new VcException('Failed sending response content.', 0, $e);
        }
    }
}
