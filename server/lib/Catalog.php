<?php

namespace Thrivecart\Server;

final class Catalog
{
    private array $cache = [];

    public function products(): array
    {
        return $this->read('products.json');
    }

    public function offers(): array
    {
        return $this->read('offer.json');
    }

    public function delivery(): array
    {
        return $this->read('delivery.json');
    }

    private function read(string $file): array
    {
        if (isset($this->cache[$file])) {
            return $this->cache[$file];
        }

        $path = dirname(__DIR__) . "/data/{$file}";
        if (!is_readable($path)) {
            throw new \RuntimeException("Unable to read {$path}");
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException("Unable to read {$path}");
        }

        $data = json_decode($contents);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON in {$path}");
        }

        if (!is_array($data)) {
            throw new \RuntimeException("Expected a JSON array in {$path}");
        }

        $this->cache[$file] = $data;

        return $data;
    }
}
