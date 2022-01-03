<?php
namespace Contented\ContentModule;

use Contented\Exception\ContentModuleNotFoundException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractContentModule implements ContentModuleInterface
{
    protected const TEMPLATE_FORMATS = [
        "content_modules/%s.html.twig",
        "content_modules/%s.twig",
    ];

    public function __construct(protected Environment $environment)
    {
    }

    protected function prepare(array $config): array
    {
        return $config;
    }

    public function render(array $config): string
    {
        $previousException = null;
        try {
            foreach (self::TEMPLATE_FORMATS as $templateFormat) {
                if ($this->environment->getLoader()->exists(sprintf($templateFormat, $this::getTag()))) {
                    return $this->environment->render(sprintf($templateFormat, $this::getTag()), $this->prepare($config));
                }
            }
        } catch (LoaderError|RuntimeError|SyntaxError $exception) {
            $previousException = $exception;
        }

        throw new ContentModuleNotFoundException($this::getTag(), $previousException);
    }
}