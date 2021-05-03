<?php
namespace Contented;

use SimpleXMLElement;

class PageConfigLoader
{
    public function resolveXmlToArray(string $filename): array
    {
        $element = simplexml_load_file($filename);
        $array = [
            'id' => ($element->attributes()['id'] instanceof SimpleXMLElement ? (string) $element->attributes()['id'] : pathinfo($filename, PATHINFO_FILENAME)),
            'layout' => (string) $element->attributes()['layout'],
            'meta' => (array) $element->meta,
            'routes' => [],
            'areas' => [],
        ];

        foreach ($element->routes->route as $route) {
            $id = pathinfo($filename, PATHINFO_FILENAME); // todo this is not unique as soon as subdirectories are supported
            if ($element->attributes()['id'] instanceof SimpleXMLElement) {
                $id = (string) $element->attributes()['id'];
            }
            $array['routes'][(string)($route->attributes()['path'])] = [
                'name' => 'contented.' . $id,
            ];
        }

        foreach ($element as $key => $item) {
            if (in_array($key, ['routes', 'meta'])) {
                continue;
            }
            foreach ($item as $content) {
                $array['areas'][$key][] = $this->recursiveXmlElementToArray($content);
            }
        }

        return $array;
    }

    private function recursiveXmlElementToArray(SimpleXMLElement $xmlElement): array
    {
        $result = [
            'type' => $xmlElement->getName(),
            'attributes' => [],
            'content' => [],
        ];

        foreach ($xmlElement->attributes() as $key => $attribute) {
            $result['attributes'][$key] = (string) $attribute;
        }

        foreach ($xmlElement as $content) {
            if ($content instanceof SimpleXMLElement) {
                $result['content'][] = $this->recursiveXmlElementToArray($content);
            } else {
                $result['content'][] = (string) $content;
            }
        }

        return $result;
    }
}