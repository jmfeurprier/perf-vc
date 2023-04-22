<?php

namespace perf\Vc\View;

use perf\Caching\CacheEntry;
use perf\Caching\Storage\CachingStorageInterface;
use perf\Timing\ClockInterface;
use Twig\Cache\CacheInterface as TwigCacheInterface;

readonly class TwigCache implements TwigCacheInterface
{
    public function __construct(
        private CachingStorageInterface $storage,
        private ClockInterface $clock
    ) {
    }

    public function generateKey(
        string $name,
        string $className
    ): string {
        $nameHash = hash('sha256', $name);
        $classNameHash = hash('sha256', $className);

        return "TWIG_{$nameHash}_{$classNameHash}";
    }

    public function write(
        string $key,
        string $content
    ): void {
        // Remove "<?php"
        $content = substr($content, 5);

        $entry = new CacheEntry($key, $content, $this->clock->getTimestamp(), null);

        $this->storage->store($entry);
    }

    /**
     * @SuppressWarnings(PHPMD.EvalExpression)
     */
    public function load(string $key): void
    {
        $entry = $this->storage->tryFetch($key);

        if ($entry) {
            $content = $entry->getContent();

            eval($content);
        }
    }

    public function getTimestamp(string $key): int
    {
        $entry = $this->storage->tryFetch($key);

        if ($entry) {
            return $entry->getCreationTimestamp();
        }

        return 0;
    }
}
