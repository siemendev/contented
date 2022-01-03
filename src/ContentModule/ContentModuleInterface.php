<?php
namespace Contented\ContentModule;

interface ContentModuleInterface
{
    public function getTag(): string;

    public function loadAdditionalData(array $data): array;
}