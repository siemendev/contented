<?php
namespace Contented\ContentRenderer;


use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Contented\ContentModule\Renderer\ContentModuleRendererInterface;
use Contented\ContentPage\Renderer\ContentPageRendererInterface;
use Contented\Exception\ContentRendererNotFoundException;

class ContentRenderer
{
    /** @var ContentPageRendererInterface[] */
    private array $contentPageRenderers = [];

    /** @var ContentModuleRendererInterface[] */
    private array $contentModuleRenderers = [];

    public function renderContentPage(ContentPageInterface $contentPage, array $config = []): string
    {
        foreach ($this->contentPageRenderers as $renderer) {
            if ($renderer->eligible($contentPage, $config)) {
                $contentAreasHtml = array_fill_keys($contentPage->getAreas(), '');
                foreach ($contentPage->getContentModules() as $area => $contentModules) {
                    foreach ($contentModules as $contentModule) {
                        $contentAreasHtml[$area] .= $this->renderContentModule($contentModule['module'], $contentModule['config']);
                    }
                }

                return $renderer->render($contentPage, $contentPage->loadAdditionalData($config), $contentAreasHtml);
            }
        }

        throw new ContentRendererNotFoundException('page with layout "' . $contentPage->getLayout() . '"');
    }

    public function renderContentModule(ContentModuleInterface $contentModule, array $config = []): string
    {
        foreach ($this->contentModuleRenderers as $renderer) {
            if ($renderer->eligible($contentModule, $config)) {
                return $renderer->render($contentModule, $contentModule->loadAdditionalData($config));
            }
        }

        throw new ContentRendererNotFoundException('module of type "' . $contentModule->getTag() . '"');
    }

    public function addContentPageRenderer(ContentPageRendererInterface $renderer): static
    {
        $this->contentPageRenderers[] = $renderer;

        return $this;
    }

    public function addContentModuleRenderer(ContentModuleRendererInterface $renderer): static
    {
        $this->contentModuleRenderers[] = $renderer;

        return $this;
    }
}