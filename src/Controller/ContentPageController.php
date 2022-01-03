<?php
namespace Contented\Controller;

use Contented\ContentLoader\ContentLoader;
use Contented\ContentRenderer\ContentRenderer;
use Contented\Settings\SettingsManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentPageController
{
    public function __construct(
        private SettingsManager $manager,
        private ContentLoader $loader,
        private ContentRenderer $renderer,
    ){
    }

    public function __invoke(Request $request): Response
    {
        $config = array_merge($request->attributes->get('_configuration'), ['settings' => $this->manager->all()]);

        return new Response($this->renderer->renderContentPage(
            $this->loader->loadContentPage($config),
            $config
        ));
    }
}