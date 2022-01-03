<?php

namespace Contented\ContentPage;

use Contented\ContentModule\ContentModuleInterface;

abstract class AbstractContentPage implements ContentPageInterface
{
    protected array $contentModules = [];

    public function addContentModule(string $area, ContentModuleInterface $contentModule, array $config): ContentPageInterface
    {
        $this->contentModules[$area][] = ['config' => $config, 'module' => $contentModule];

        return $this;
    }

    public function getContentModules(): array
    {
        return $this->contentModules;
    }

    public function loadAdditionalData(array $data): array
    {
        return $data;
    }
}