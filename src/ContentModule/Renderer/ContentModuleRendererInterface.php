<?php

namespace Contented\ContentModule\Renderer;

use Contented\ContentModule\ContentModuleInterface;

interface ContentModuleRendererInterface
{
    public function eligible(ContentModuleInterface $contentModule, array $config): bool;

    public function render(ContentModuleInterface $contentModule, array $config): string;
}