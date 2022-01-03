<?php

namespace Contented\ContentPage;

use Contented\ContentModule\ContentModuleInterface;
use Contented\Exception\ContentPageNotFoundException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractContentPage implements ContentPageInterface
{
    protected const TEMPLATE_FORMATS = [
        "content_pages/%s.html.twig",
        "content_pages/%s.twig",
    ];

    protected array $contentModules = [];

    public function __construct(protected Environment $environment)
    {
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

        $previousException = null;
        try {
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
        } catch (LoaderError|RuntimeError|SyntaxError $exception) {
            $previousException = $exception;
        }

        throw new ContentPageNotFoundException($this::getLayout(), $previousException);
    }
}