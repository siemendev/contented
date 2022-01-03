<?php
namespace Contented\Settings;

use JsonException;
use SimpleXMLElement;

class SettingsManager
{
    private array $cache = [];
    private array $securedCache = [];

    public function __construct(string $contentPath)
    {
        $this->buildCache($contentPath);
    }

    public function resolve(string $key): mixed
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }
        if (array_key_exists($key, $this->securedCache)) {
            return $this->securedCache[$key];
        }

        return null;
    }

    public function all(bool $includeSecured = false): array
    {
        return array_merge($this->cache, $includeSecured ? $this->securedCache : []);
    }

    private function buildCache(string $contentPath): void
    {
        $content = file_get_contents($contentPath . '/settings.xml');
        if (empty($content)) {
            return;
        }

        $element = simplexml_load_string($content);
        if (!$element instanceof SimpleXMLElement) {
            return;
        }

        foreach ($element->children() as $tag => $child) {
            $value = (string) $child[0];
            try {
                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
            }

            if (isset($child->attributes()->secured)) {
                $this->securedCache[$tag] = $value;
                continue;
            }

            $this->cache[$tag] = $value;
        }
    }
}