<?php

namespace Contented\ContentPage;

use Contented\ContentModule\ContentModuleInterface;
use LogicException;
use Twig\Environment;

abstract class AbstractContentPage implements ContentPageInterface
{
    protected const TEMPLATE_FORMATS = [
        "content_pages/%s.html.twig",
        "content_pages/%s.twig",
    ];

    /** @var ContentModuleInterface[] */
    protected $contentModules = [];

    /** @var Environment */
    protected $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function addContentModule(string $area, ContentModuleInterface $contentModule, array $config): ContentPageInterface
    {
        $this->contentModules[$area][] = ['config' => $config, 'module' => $contentModule];

        return $this;
    }

    protected function prepare(array $config): array
    {
        return $config;
    }

    public function render(array $config): string
    {
        $contentAreasHtml = [];
        foreach ($this->contentModules as $area => $contentModules) {
            foreach ($contentModules as $contentModule) {
                $contentAreasHtml[$area] = ($contentAreasHtml[$area] ?? '') . $contentModule['module']->render($contentModule['config']);
            }
        }

        foreach (self::TEMPLATE_FORMATS as $templateFormat) {
            if ($this->environment->getLoader()->exists(sprintf($templateFormat, $this::getLayout()))) {
                return $this->environment->render(
                    sprintf($templateFormat, $this::getLayout()),
                    $this->prepare([
                        'config' => $config,
                        'content_areas' => $contentAreasHtml,
                    ])
                );
            }
        }

        // todo add exception text
        throw new LogicException('Could not find page template');
    }
}