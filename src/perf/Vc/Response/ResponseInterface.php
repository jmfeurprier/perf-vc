<?php

namespace perf\Vc\Response;

/**
 * Response.
 *
 */
interface ResponseInterface
{

    /**
     *
     *
     * @return void
     */
    public function send();

    /**
     *
     *
     * @return void
     */
    public function sendHeaders();

    /**
     *
     *
     * @return void
     */
    public function sendContent();

    /**
     *
     *
     * @return string[]
     */
    public function getHeaders();

    /**
     *
     *
     * @return string
     */
    public function getContent();
}
