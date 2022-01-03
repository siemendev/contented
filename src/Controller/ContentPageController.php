<?php
namespace Contented\Controller;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Contented\Exception\ContentModuleNotFoundException;
use Contented\Exception\ContentPageNotFoundException;
use Contented\Settings\SettingsManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentPageController
{
    /** @var ContentModuleInterface[] */
    private array $contentModules = [];

    /** @var ContentPageInterface[] */
    private array $contentPages = [];

    public function __construct(private SettingsManager $manager)
    {
    }

    public function __invoke(Request $request): Response
    {
        $config = array_merge($request->attributes->get('_configuration'), ['settings' => $this->manager->all()]);

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

        throw new ContentPageNotFoundException($config['layout']);
    }

    private function resolveContentModule(array $config): ContentModuleInterface
    {
        foreach ($this->contentModules as $contentModule) {
            if ($contentModule::getTag() === $config['type']) {
                return (clone $contentModule);
            }
        }

        throw new ContentModuleNotFoundException($config['type']);
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