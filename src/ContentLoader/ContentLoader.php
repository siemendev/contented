<?php

namespace Contented\ContentLoader;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Contented\Exception\ContentModuleNotFoundException;
use Contented\Exception\ContentPageNotFoundException;

class ContentLoader
{
    /** @var ContentLoaderInterface[] */
    private array $contentLoaders = [];

    /**
     * @throws ContentPageNotFoundException
     * @throws ContentModuleNotFoundException
     */
    public function loadContentPage(array $config): ContentPageInterface
    {
        foreach ($this->contentLoaders as $loader) {
            try {
                $contentPage = $loader->getContentPage($config);
                foreach ($config['areas'] as $area => $content) {
                    foreach ($content as $module) {
                        $contentPage->addContentModule($area, $this->loadContentModule($module), $module);
                    }
                }
                return $contentPage;
            } catch (ContentPageNotFoundException) {
            }
        }

        throw new ContentPageNotFoundException($config['layout']);
    }

    public function loadContentModule(array $config): ContentModuleInterface
    {
        foreach ($this->contentLoaders as $loader) {
            try {
                return $loader->getContentModule($config);
            } catch (ContentModuleNotFoundException) {
            }
        }

        throw new ContentModuleNotFoundException($config['type']);
    }

    public function addContentLoader(ContentLoaderInterface $loader): static
    {
        $this->contentLoaders[] = $loader;

        return $this;
    }
}