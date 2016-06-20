<?php

namespace perf\Vc\Request;

/**
 * HTTP request populator.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
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
     * @return RequestInterface
     * @throws \RuntimeException
     */
    public function populate()
    {
        $query        = isset($_GET) ? $_GET : array();
        $cookies      = isset($_COOKIE) ? $_COOKIE : array();
        $this->server = isset($_SERVER) ? $_SERVER : array();

        $method     = $this->getMethod();
        $path       = $this->getPath();
        $attachment = $this->getAttachment($method);

        return new Request($method, $path, $query, $attachment, $cookies, $this->server);
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
     * @param string $method
     * @return array
     */
    private function getAttachment($method)
    {
        $attachment = array();

        switch ($method) {
            case 'POST':
                $attachment = $_POST;
                break;

            case 'DELETE':
            case 'PATCH':
            case 'PUT':
                parse_str(file_get_contents('php://input'), $attachment);
                break;
        }

        if (isset($_FILES)) {
            foreach ($_FILES as $key => $file) {
                $attachment[$key] = $file;
            }
        }

        return $attachment;
    }
}
