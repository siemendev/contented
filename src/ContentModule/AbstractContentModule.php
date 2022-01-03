<?php

namespace Contented\ContentModule;

abstract class AbstractContentModule implements ContentModuleInterface
{
    public function loadAdditionalData(array $data): array
    {
        return $data;
    }
}