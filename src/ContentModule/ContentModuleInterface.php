<?php
namespace Contented\ContentModule;

interface ContentModuleInterface
{
    public static function getTag(): string;

    public function render(array $config): string;
}