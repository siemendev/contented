<?php
namespace Contented\ContentModule;

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

    /** @var Environment */
    protected $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    protected function prepare(array $config): array
    {
        return $config;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(array $config): string
    {
        foreach (self::TEMPLATE_FORMATS as $templateFormat) {
            if ($this->environment->getLoader()->exists(sprintf($templateFormat, $this::getTag()))) {
                return $this->environment->render(sprintf($templateFormat, $this::getTag()), $this->prepare($config));
            }
        }

        // TODO throw logic exception to help developers finding their way
        return '';
    }
}