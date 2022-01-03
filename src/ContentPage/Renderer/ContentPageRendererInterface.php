<?php

namespace Contented\ContentPage\Renderer;

use Contented\ContentPage\ContentPageInterface;

interface ContentPageRendererInterface
{
    public function eligible(ContentPageInterface $contentPage, array $config): bool;

    public function render(ContentPageInterface $contentPage, array $config, array $contentAreasHtml): string;
}