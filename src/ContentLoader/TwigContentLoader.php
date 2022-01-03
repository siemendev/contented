<?php

namespace Contented\ContentLoader;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentModule\GenericContentModule;
use Contented\ContentModule\Renderer\TwigContentModuleRenderer;
use Contented\ContentPage\ContentPageInterface;
use Contented\ContentPage\GenericContentPage;
use Contented\ContentPage\Renderer\TwigContentPageRenderer;
use Contented\Exception\ContentModuleNotFoundException;
use Contented\Exception\ContentPageNotFoundException;

class TwigContentLoader implements ContentLoaderInterface
{
    public function __construct(
        private TwigContentPageRenderer $pageRenderer,
        private TwigContentModuleRenderer $moduleRenderer,
    ){
    }

    public function getContentPage(array $config): ContentPageInterface
    {
        $contentPage = new GenericContentPage();
        $contentPage->layout = $config['layout'];

        if ($this->pageRenderer->eligible($contentPage, $config)) {
            foreach ($config['areas'] as $area => $content) {
                foreach ($content as $module) {
                    $contentPage->addContentModule($area, $this->getContentModule($module), $module);
                }
            }

            return $contentPage;
        }

        throw new ContentPageNotFoundException($config['layout']);
    }

    public function getContentModule(array $config): ContentModuleInterface
    {
        $contentModule = new GenericContentModule();
        $contentModule->tag = $config['type'];

        if ($this->moduleRenderer->eligible($contentModule, $config)) {
            return $contentModule;
        }

        throw new ContentModuleNotFoundException($config['type']);
    }
}