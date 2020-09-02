<?php

namespace perf\Vc\Response;

class ResponseSender implements ResponseSenderInterface
{
    private ResponseInterface $response;

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

    public function sendContent(): void
    {
        $this->response->getContent()->send();
    }
}
