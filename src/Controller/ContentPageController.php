<?php
namespace Contented\Controller;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentPageController
{
    /** @var ContentModuleInterface[] */
    private $contentModules = [];

    /** @var ContentPageInterface[] */
    private $contentPages = [];

    public function __invoke(Request $request): Response
    {
        $config = $request->attributes->get('_configuration');

        $contentPage = $this->resolvePageLayout($config);

        foreach ($config['areas'] as $area => $content) {
            foreach ($content as $module) {
                $contentPage->addContentModule($area, $this->resolveContentModule($module), $module);
            }
        }

        return new Response($contentPage->render($config));
    }

    private function resolvePageLayout(array $config): ContentPageInterface
    {
        foreach ($this->contentPages as $contentPage) {
            if ($contentPage::getLayout() === $config['layout']) {
                return (clone $contentPage);
            }
        }

        throw new LogicException('Could not find content page ' . $config['layout']);
    }

    private function resolveContentModule(array $config): ContentModuleInterface
    {
        foreach ($this->contentModules as $contentModule) {
            if ($contentModule::getTag() === $config['type']) {
                return (clone $contentModule);
            }
        }

        throw new LogicException('Could not find content page ' . $config['type']);
    }

    public function addContentModule($contentModule): ContentPageController
    {
        $this->contentModules[] = $contentModule;

        return $this;
    }

    public function addContentPage($contentPage): ContentPageController
    {
        $this->contentPages[] = $contentPage;

        return $this;
    }
}