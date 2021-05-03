<?php
namespace Contented\ContentPage;

use Contented\ContentModule\ContentModuleInterface;

interface ContentPageInterface
{
    public static function getLayout(): string;

    public static function getAreas(): array;

    public function addContentModule(string $area, ContentModuleInterface $contentModule, array $config): ContentPageInterface;

    public function render(array $config): string;
}