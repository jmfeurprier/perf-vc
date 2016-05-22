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
     * @throws \RuntimeException
     */
    public function populate()
    {
        $query        = isset($_GET) ? $_GET : array();
        $cookies      = isset($_COOKIE) ? $_COOKIE : array();
        $this->server = isset($_SERVER) ? $_SERVER : array();

        $method = $this->getMethod();
        $path   = $this->getPath();
        $post   = $this->getPost();

        return new Request($method, $path, $query, $post, $cookies, $this->server);
    }

    /**
     *
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getMethod()
    {
        if (array_key_exists('REQUEST_METHOD', $this->server)) {
            return $this->server['REQUEST_METHOD'];
        }

        throw new \RuntimeException('Failed to retrieve HTTP method.');
    }

    /**
     *
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getPath()
    {
        if (array_key_exists('REDIRECT_URL', $this->server)) {
            $url = $this->server['REDIRECT_URL'];
        } elseif (array_key_exists('REQUEST_URI', $this->server)) {
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
