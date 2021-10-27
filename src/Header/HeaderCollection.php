<?php

namespace perf\Vc\Header;

class HeaderCollection
{
    /**
     * @var Header[]
     */
    private array $headers = [];

    public function __construct(array $headers = [])
    {
        $this->addMany($headers);
    }

    public function addMany(array $headers): void
    {
        foreach ($headers as $header) {
            $this->add($header);
        }
    }

    public function add(Header $header)
    {
        $this->headers[] = $header;
    }

    public function has(string $key): bool
    {
        return (count($this->search($key)) > 0);
    }

    /**
     * @return Header[]
     */
    public function get(string $key): array
    {
        $headers = [];

        foreach ($this->search($key) as $collectionKey) {
            $headers[] = $this->headers[$collectionKey];
        }

        return $headers;
    }

    /**
     * @return Header[]
     */
    public function getAll(): array
    {
        return $this->headers;
    }

    public function replace(Header $header): void
    {
        $this->remove($header->getKey());
        $this->add($header);
    }

    public function remove(string $key): void
    {
        foreach ($this->search($key) as $collectionKey) {
            unset($this->headers[$collectionKey]);
        }
    }

    public function removeAll(): void
    {
        $this->headers = [];
    }

    /**
     * @return int[]
     */
    private function search(string $key): array
    {
        $collectionKeys = [];

        foreach ($this->headers as $collectionKey => $header) {
            if ($key === $header->getKey()) {
                $collectionKeys[] = $collectionKey;
            }
        }

        return $collectionKeys;
    }
}
