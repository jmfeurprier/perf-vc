<?php

namespace perf\Vc;

/**
 * HTTP request populator.
 *
 */
class RequestPopulator
{

    /**
     * Server values container.
     * Temporary property.
     *
     * @var {string:mixed}
     */
    private $server;

    /**
     * Returns a new HTTP request instance, populated with global values.
     *
     * @return Request
     * @throws \Exception
     */
    public function populate()
    {
        $get          = $_GET;
        $cookies      = $_COOKIE;
        $this->server = $_SERVER;

        $method = $this->getMethod();
        $path   = $this->getPath();
        $post   = $this->getPost();

        return new Request($method, $path, $get, $post, $cookies, $this->server);
    }

    /**
     *
     *
     * @return string
     */
    private function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     *
     *
     * @return string
     */
    private function getPath()
    {
        if (isset($this->server['REDIRECT_URL'])) {
            $url = $this->server['REDIRECT_URL'];
        } elseif (isset($this->server['REQUEST_URI'])) {
            $url = $this->server['REQUEST_URI'];
        } else {
            throw new \RuntimeException('Failed to retrieve HTTP request path.');
        }

        $path = parse_url($url, \PHP_URL_PATH);

        if (false === $path) {
            throw new \RuntimeException("Failed to retrieve HTTP request path from URL '{$url}'.");
        }

        return $path;
    }

    /**
     *
     *
     * @return array
     */
    private function getPost()
    {
        $post = $_POST;

        if (isset($_FILES)) {
            foreach ($_FILES as $key => $file) {
                $fileKey = "@{$key}";

                $post[$fileKey] = $file;
            }
        }

        return $post;
    }
}
