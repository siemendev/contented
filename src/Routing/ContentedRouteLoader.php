<?php
namespace Contented\Routing;

use Contented\PageConfigLoader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ContentedRouteLoader extends Loader
{
    const LANGUAGES = ['de'];

    /** @var PageConfigLoader */
    private $pageConfigLoader;

    public function setPageConfigLoader(PageConfigLoader $pageConfigLoader): ContentedRouteLoader
    {
        $this->pageConfigLoader = $pageConfigLoader;

        return $this;
    }

    public function load($resource, string $type = null)
    {
        $routes = new RouteCollection();

        // todo better i18n support
        foreach (self::LANGUAGES as $language) {
            foreach (glob("var/content/$language/*.xml") as $filename) {
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

    public function supports($resource, string $type = null)
    {
        return 'contented';
    }
}