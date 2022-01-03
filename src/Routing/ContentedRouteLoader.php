<?php
namespace Contented\Routing;

use Contented\PageConfigLoader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ContentedRouteLoader extends Loader
{
    public function __construct(
        private PageConfigLoader $pageConfigLoader,
        private string $contentPath,
        private array $languages,
    ) {
        parent::__construct();
    }

    public function load($resource, string $type = null): RouteCollection
    {
        $routes = new RouteCollection();

        foreach ($this->languages as $language) {
            foreach (glob($this->contentPath . "/$language/*.xml") as $filename) {
                $configuration = $this->pageConfigLoader->resolveXmlToArray($filename);

                foreach ($configuration['routes'] as $path => $options) {
                    $routes->add(
                        $options['name'],
                        new Route(
                            $path,
                            [
                                '_controller' => 'contented.controller',
                                '_configuration' => $configuration
                            ]
                        )
                    );
                }
            }
        }

        return $routes;
    }

    public function supports($resource, string $type = null): bool
    {
        return $type === 'contented';
    }
}