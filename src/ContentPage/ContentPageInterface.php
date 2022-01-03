<?php
namespace Contented\ContentPage;

use Contented\ContentModule\ContentModuleInterface;

interface ContentPageInterface
{
    public function getLayout(): string;

    public function getAreas(): array;

    public function addContentModule(string $area, ContentModuleInterface $contentModule, array $config): ContentPageInterface;

    public function getContentModules(): array;

    public function loadAdditionalData(array $data): array;
}