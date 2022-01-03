<?php
namespace Contented\ContentLoader;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Contented\Exception\ContentModuleNotFoundException;
use Contented\Exception\ContentPageNotFoundException;

class ServiceContentLoader implements ContentLoaderInterface
{
    /** @var ContentPageInterface[] */
    private array $contentPages = [];

    /** @var ContentModuleInterface[] */
    private array $contentModules = [];

    /**
     * @throws ContentPageNotFoundException
     */
    public function getContentPage(array $config): ContentPageInterface
    {
        foreach ($this->contentPages as $contentPage) {
            if ($contentPage->getLayout() === $config['layout']) {
                return clone $contentPage;
            }
        }

        throw new ContentPageNotFoundException($config['layout']);
    }

    public function getContentModule(array $config): ContentModuleInterface
    {
        foreach ($this->contentModules as $contentModule) {
            if ($contentModule->getTag() === $config['type']) {
                return clone $contentModule;
            }
        }

        throw new ContentModuleNotFoundException($config['type']);
    }

    public function addContentPage(ContentPageInterface $contentPage): static
    {
        $this->contentPages[] = $contentPage;

        return $this;
    }

    public function addContentModule(ContentModuleInterface $contentModule): static
    {
        $this->contentModules[] = $contentModule;

        return $this;
    }
}